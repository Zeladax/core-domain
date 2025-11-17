<?php

namespace IncadevUns\CoreDomain\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use IncadevUns\CoreDomain\Enums\TicketPriority;
use IncadevUns\CoreDomain\Enums\TicketStatus;
use IncadevUns\CoreDomain\Enums\TicketType;
use IncadevUns\CoreDomain\Models\Ticket;
use IncadevUns\CoreDomain\Models\TicketReply;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder se encarga de configurar aspectos tecnológicos del sistema:
     * - Asignación de permisos al rol admin
     * - Asignación de permisos de soporte técnico a roles
     * - Asignación de permisos de seguridad a roles
     * - Datos de muestra para el módulo de soporte técnico
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🔧 Ejecutando TechnologySeeder...');
        $this->command->info('');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->assignAdminPermissions();
        $this->assignSupportTechnicalPermissions();
        $this->assignSecurityPermissions();
        $this->seedSupportTechnicalSampleData();

        $this->command->info('');
        $this->command->info('✅ TechnologySeeder completado exitosamente!');
    }

    /**
     * Asignar todos los permisos al rol admin
     */
    private function assignAdminPermissions(): void
    {
        $this->command->info('🔐 Asignando permisos al rol admin...');

        // Obtener el rol admin
        $adminRole = Role::where('name', 'admin')->first();

        if (! $adminRole) {
            $this->command->error('❌ El rol "admin" no existe. Por favor, créalo primero.');

            return;
        }

        $this->command->info('✅ Rol admin encontrado!');

        // Obtener TODOS los permisos de la base de datos
        $allPermissions = Permission::all();

        if ($allPermissions->isEmpty()) {
            $this->command->error('❌ No hay permisos en la base de datos. Ejecuta primero el PermissionsSeeder.');

            return;
        }

        $this->command->info('🔄 Asignando '.$allPermissions->count().' permisos al rol admin...');

        // Asignar TODOS los permisos al rol admin
        $adminRole->syncPermissions($allPermissions);

        $this->command->info('✅ Todos los permisos han sido asignados exitosamente al rol admin!');
        $this->command->info('');
        $this->command->info('📊 Resumen:');
        $this->command->info('   - Rol: admin');
        $this->command->info('   - Total de permisos asignados: '.$allPermissions->count());
        $this->command->info('');
    }

    /**
     * Asignar permisos del módulo de soporte técnico a los roles
     */
    private function assignSupportTechnicalPermissions(): void
    {
        $this->command->info('🎫 Asignando permisos de soporte técnico a roles...');

        // Obtener roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $supportRole = Role::where('name', 'support')->first();

        // Obtener todos los roles regulares (excluyendo admin, super_admin, y support)
        $regularRoles = Role::whereNotIn('name', ['admin', 'super_admin', 'support'])->get();

        // Permisos de tickets
        $ticketPermissions = [
            'tickets.view-any',
            'tickets.view',
            'tickets.create',
            'tickets.update',
            'tickets.delete',
        ];

        // Permisos de respuestas
        $replyPermissions = [
            'ticket-replies.create',
            'ticket-replies.update',
            'ticket-replies.delete',
        ];

        // Permisos de adjuntos
        $attachmentPermissions = [
            'reply-attachments.delete',
        ];

        $allTicketPermissions = array_merge($ticketPermissions, $replyPermissions, $attachmentPermissions);

        // Asignar permisos a super_admin (todos los permisos)
        if ($superAdminRole) {
            foreach ($allTicketPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $superAdminRole->givePermissionTo($perm);
                }
            }
        }

        // Asignar permisos a admin (todos los permisos)
        if ($adminRole) {
            foreach ($allTicketPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $adminRole->givePermissionTo($perm);
                }
            }
        }

        // Asignar permisos a support (todos los permisos)
        if ($supportRole) {
            foreach ($allTicketPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $supportRole->givePermissionTo($perm);
                }
            }
        }

        // Asignar permisos básicos a roles regulares (solo crear y ver sus propios tickets)
        $regularUserPermissions = [
            'tickets.view',
            'tickets.create',
            'ticket-replies.create',
        ];

        foreach ($regularRoles as $role) {
            foreach ($regularUserPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $role->givePermissionTo($perm);
                }
            }
        }

        $this->command->info('✓ Permisos del módulo SupportTechnical asignados correctamente');
        $this->command->info('  - super_admin: Todos los permisos');
        $this->command->info('  - admin: Todos los permisos');
        $this->command->info('  - support: Todos los permisos');
        $this->command->info('  - Roles regulares ('.$regularRoles->count().'): tickets.view, tickets.create, ticket-replies.create');
        $this->command->info('');
    }

    /**
     * Asignar permisos del módulo de seguridad a los roles
     */
    private function assignSecurityPermissions(): void
    {
        $this->command->info('🔒 Asignando permisos de seguridad a roles...');

        // Permisos básicos (para usuarios normales)
        $basicPermissions = [
            'security-dashboard.view',
            'sessions.view',
            'sessions.terminate',
            'tokens.view',
            'tokens.revoke',
            'security-events.view',
        ];

        // Permisos administrativos (para rol security)
        $adminPermissions = [
            'security-dashboard.view-any',
            'sessions.view-any',
            'sessions.terminate-any',
            'tokens.view-any',
            'tokens.revoke-any',
            'security-events.view-any',
            'security-events.export',
            'security-alerts.view',
            'security-alerts.resolve',
            'security-users.view',
            'security-users.block',
            'security-users.unblock',
        ];

        $allSecurityPermissions = array_merge($basicPermissions, $adminPermissions);

        // Obtener o crear rol security
        $securityRole = Role::where('name', 'security')->first();

        if ($securityRole) {
            // Asignar TODOS los permisos al rol security
            foreach ($allSecurityPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $securityRole->givePermissionTo($perm);
                }
            }
            $this->command->info('✅ Rol "security" tiene acceso global al módulo de seguridad');
        }

        // Asignar permisos al rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            foreach ($allSecurityPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $adminRole->givePermissionTo($perm);
                }
            }
            $this->command->info('✅ Rol "admin" tiene acceso completo al módulo de seguridad');
        }

        // Asignar permisos al rol super_admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            foreach ($allSecurityPermissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $superAdminRole->givePermissionTo($perm);
                }
            }
            $this->command->info('✅ Rol "super_admin" tiene acceso completo al módulo de seguridad');
        }

        $this->command->info('');
    }

    /**
     * Generar datos de muestra para el módulo de soporte técnico
     */
    private function seedSupportTechnicalSampleData(): void
    {
        $this->command->info('🎫 Generando datos de muestra para SupportTechnical...');

        $userModelClass = config('auth.providers.users.model', 'App\Models\User');

        // Obtener usuarios con diferentes roles
        $regularUsers = $userModelClass::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super_admin', 'support']);
        })->limit(5)->get();

        $supportUsers = $userModelClass::whereHas('roles', function ($query) {
            $query->whereIn('name', ['support', 'admin']);
        })->limit(2)->get();

        if ($regularUsers->isEmpty()) {
            $this->command->error('✗ No se encontraron usuarios regulares (sin roles admin, super_admin o support)');
            $this->command->warn('Por favor, ejecuta primero el seeder de usuarios');

            return;
        }

        if ($supportUsers->isEmpty()) {
            $this->command->warn('⚠ No se encontraron usuarios con rol "support" o "admin"');
            $this->command->info('Los tickets se crearán sin respuestas de soporte.');
        }

        DB::transaction(function () use ($regularUsers, $supportUsers) {
            $ticketsCreated = 0;
            $repliesCreated = 0;

            // Datos de muestra de tickets
            $ticketSamples = [
                // OPEN tickets
                [
                    'title' => 'No puedo acceder al sistema LMS',
                    'description' => 'Desde esta mañana no puedo ingresar al sistema LMS. Me aparece un error de "Credenciales inválidas" aunque estoy usando mi contraseña correcta.',
                    'type' => TicketType::Technical,
                    'priority' => TicketPriority::High,
                    'status' => TicketStatus::Open,
                    'replies_count' => 0,
                ],
                [
                    'title' => 'Solicitud de certificado académico',
                    'description' => 'Necesito un certificado de estudios para presentar en mi nuevo trabajo. ¿Cómo puedo solicitarlo?',
                    'type' => TicketType::Academic,
                    'priority' => TicketPriority::Medium,
                    'status' => TicketStatus::Open,
                    'replies_count' => 1,
                ],
                [
                    'title' => '¿Cómo exportar reportes a Excel?',
                    'description' => 'Necesito saber cómo puedo exportar los reportes del módulo de análisis de datos a formato Excel. No encuentro la opción.',
                    'type' => TicketType::Inquiry,
                    'priority' => TicketPriority::Low,
                    'status' => TicketStatus::Open,
                    'replies_count' => 2,
                ],
                // PENDING tickets
                [
                    'title' => 'Error al subir archivos grandes',
                    'description' => 'Cuando intento subir archivos mayores a 10MB, el sistema se queda cargando y eventualmente da timeout.',
                    'type' => TicketType::Technical,
                    'priority' => TicketPriority::High,
                    'status' => TicketStatus::Pending,
                    'replies_count' => 3,
                ],
                [
                    'title' => 'Actualización de datos personales',
                    'description' => 'Necesito actualizar mi dirección y número de teléfono en el sistema administrativo.',
                    'type' => TicketType::Administrative,
                    'priority' => TicketPriority::Low,
                    'status' => TicketStatus::Pending,
                    'replies_count' => 2,
                ],
                // CLOSED tickets
                [
                    'title' => 'No recibo notificaciones por correo',
                    'description' => 'Configuré las notificaciones pero no me llegan los correos. Ya revisé mi bandeja de spam.',
                    'type' => TicketType::Technical,
                    'priority' => TicketPriority::Medium,
                    'status' => TicketStatus::Closed,
                    'replies_count' => 4,
                ],
                [
                    'title' => 'Solicitud de constancia de matrícula',
                    'description' => 'Por favor, necesito una constancia de matrícula vigente para el trámite de beca.',
                    'type' => TicketType::Academic,
                    'priority' => TicketPriority::Medium,
                    'status' => TicketStatus::Closed,
                    'replies_count' => 2,
                ],
                [
                    'title' => '¿Cómo cambiar mi contraseña?',
                    'description' => 'Necesito instrucciones para cambiar mi contraseña de acceso al sistema.',
                    'type' => TicketType::Inquiry,
                    'priority' => TicketPriority::Low,
                    'status' => TicketStatus::Closed,
                    'replies_count' => 1,
                ],
                // More OPEN tickets
                [
                    'title' => 'Dashboard no carga las estadísticas',
                    'description' => 'El dashboard principal se queda en blanco cuando intento ver las estadísticas del mes.',
                    'type' => TicketType::Technical,
                    'priority' => TicketPriority::High,
                    'status' => TicketStatus::Open,
                    'replies_count' => 0,
                ],
                [
                    'title' => 'Consulta sobre horarios de atención',
                    'description' => '¿Cuáles son los horarios de atención de la oficina de registro académico?',
                    'type' => TicketType::Inquiry,
                    'priority' => TicketPriority::Low,
                    'status' => TicketStatus::Open,
                    'replies_count' => 1,
                ],
            ];

            // Crear tickets
            foreach ($ticketSamples as $index => $ticketData) {
                $user = $regularUsers[$index % $regularUsers->count()];
                $repliesCount = $ticketData['replies_count'];
                unset($ticketData['replies_count']);

                // Crear ticket
                $ticket = Ticket::create([
                    'user_id' => $user->id,
                    'title' => $ticketData['title'],
                    'type' => $ticketData['type'],
                    'priority' => $ticketData['priority'],
                    'status' => $ticketData['status'],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);

                $ticketsCreated++;

                // Crear respuesta inicial (descripción del ticket)
                TicketReply::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'content' => $ticketData['description'],
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->created_at,
                ]);

                $repliesCreated++;

                // Crear respuestas adicionales si se especificaron
                if ($repliesCount > 0 && $supportUsers->isNotEmpty()) {
                    for ($i = 0; $i < $repliesCount; $i++) {
                        $isFromSupport = $i % 2 === 0;
                        $replyUser = $isFromSupport
                            ? $supportUsers[$i % $supportUsers->count()]
                            : $user;

                        $replyContent = $this->generateReplyContent($ticketData['type'], $isFromSupport, $i);

                        TicketReply::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $replyUser->id,
                            'content' => $replyContent,
                            'created_at' => $ticket->created_at->addHours(($i + 1) * 3),
                            'updated_at' => $ticket->created_at->addHours(($i + 1) * 3),
                        ]);

                        $repliesCreated++;
                    }
                }
            }

            $this->command->info("✓ {$ticketsCreated} tickets creados");
            $this->command->info("✓ {$repliesCreated} respuestas creadas");
        });

        $this->command->info('✓ Datos de muestra generados exitosamente');
        $this->command->info('');
    }

    /**
     * Generar contenido de respuesta apropiado basado en el tipo de ticket
     */
    private function generateReplyContent(TicketType $type, bool $isFromSupport, int $replyIndex): string
    {
        if ($isFromSupport) {
            $supportReplies = [
                TicketType::Technical->value => [
                    'Gracias por reportar el problema técnico. Nuestro equipo está investigando el caso.',
                    'Hemos identificado la causa del problema. Estamos trabajando en la solución.',
                    'El problema ha sido resuelto. Por favor, confirma si ahora funciona correctamente.',
                ],
                TicketType::Academic->value => [
                    'Recibimos tu solicitud académica. Estamos procesándola.',
                    'Tu solicitud ha sido aprobada y está en proceso.',
                    'La solicitud ha sido completada. Por favor, verifica.',
                ],
                TicketType::Administrative->value => [
                    'Tu solicitud administrativa está siendo revisada por el área correspondiente.',
                    'Hemos procesado tu solicitud. Te enviaremos la documentación por correo.',
                ],
                TicketType::Inquiry->value => [
                    'Gracias por tu consulta. Te proporciono la siguiente información:',
                    'Para realizar eso, debes seguir estos pasos: 1) Ir al menú principal, 2) Seleccionar la opción correspondiente.',
                ],
            ];

            $replies = $supportReplies[$type->value] ?? ['Gracias por contactarnos.'];

            return $replies[$replyIndex % count($replies)];
        } else {
            $userReplies = [
                'Gracias por la respuesta. Entiendo.',
                'Perfecto, ya probé y funciona correctamente.',
                '¿Podrían darme más detalles sobre esto?',
                'Muchas gracias por la ayuda.',
                'El problema persiste, aún no funciona.',
            ];

            return $userReplies[$replyIndex % count($userReplies)];
        }
    }
}

<?php

namespace IncadevUns\CoreDomain\Database\Seeders;

use Illuminate\Database\Seeder;
use IncadevUns\CoreDomain\Enums\AppointmentStatus;
use IncadevUns\CoreDomain\Models\Appointment;
use IncadevUns\CoreDomain\Models\Availability;
use IncadevUns\CoreDomain\Models\Comment;
use IncadevUns\CoreDomain\Models\Forum;
use IncadevUns\CoreDomain\Models\Thread;
use IncadevUns\CoreDomain\Models\Vote;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder se encarga de configurar aspectos de soporte del sistema:
     * - Disponibilidad de horarios para orientación
     * - Citas entre docentes y estudiantes
     * - Foros de comunidad
     * - Hilos de discusión
     * - Comentarios y votos
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🛠️ Ejecutando SupportSeeder...');
        $this->command->info('');

        $userModelClass = config('auth.providers.users.model', 'App\\Models\\User');

        // -----------------------------------
        // 1. AvailabilitySeeder
        // -----------------------------------
        $this->command->info('📅 Creando disponibilidades de docentes...');

        $teachers = [
            'ana@incadev.com' => [
                [1, '09:00', '17:00'], // Lunes
                [3, '10:00', '14:00'], // Miércoles
                [5, '09:00', '12:00'], // Viernes
            ],
            'dante@incadev.com' => [
                [2, '08:00', '12:00'], // Martes
                [4, '13:00', '17:00'], // Jueves
            ],
            'antonio@incadev.com' => [
                [1, '10:00', '16:00'], // Lunes
                [3, '09:00', '13:00'], // Miércoles
                [5, '08:00', '11:00'], // Viernes
            ],
        ];

        foreach ($teachers as $email => $schedules) {
            $teacher = $userModelClass::where('email', $email)->first();

            if (! $teacher) {
                $this->command->warn("⚠️ No se encontró el usuario con email {$email}, se omitió.");

                continue;
            }

            foreach ($schedules as [$day, $start, $end]) {
                Availability::firstOrCreate([
                    'user_id' => $teacher->id,
                    'day_of_week' => $day,
                    'start_time' => $start,
                    'end_time' => $end,
                ]);
            }
        }

        $this->command->info('✅ Disponibilidades creadas exitosamente!');
        $this->command->info('');

        // -----------------------------------
        // 2. AppointmentSeeder
        // -----------------------------------
        $this->command->info('🗓️ Creando citas de orientación...');

        $teacherEmails = ['ana@incadev.com', 'dante@incadev.com', 'antonio@incadev.com'];
        $studentEmails = ['liliana@incadev.com', 'pedro@incadev.com', 'sofia@incadev.com', 'javier@incadev.com', 'valentina@incadev.com'];

        // Citas específicas con diferentes estados
        $appointmentsData = [
            ['teacher' => 'ana@incadev.com', 'student' => 'liliana@incadev.com', 'status' => AppointmentStatus::Completed, 'days_ago' => 5],
            ['teacher' => 'ana@incadev.com', 'student' => 'sofia@incadev.com', 'status' => AppointmentStatus::Confirmed, 'days_ago' => -2],
            ['teacher' => 'dante@incadev.com', 'student' => 'pedro@incadev.com', 'status' => AppointmentStatus::Rejected, 'days_ago' => 3],
            ['teacher' => 'dante@incadev.com', 'student' => 'javier@incadev.com', 'status' => AppointmentStatus::Completed, 'days_ago' => 7],
            ['teacher' => 'antonio@incadev.com', 'student' => 'valentina@incadev.com', 'status' => AppointmentStatus::Cancelled, 'days_ago' => 1],
            ['teacher' => 'antonio@incadev.com', 'student' => 'liliana@incadev.com', 'status' => AppointmentStatus::Pending, 'days_ago' => -1],
            ['teacher' => 'ana@incadev.com', 'student' => 'pedro@incadev.com', 'status' => AppointmentStatus::Completed, 'days_ago' => 10],
            ['teacher' => 'dante@incadev.com', 'student' => 'sofia@incadev.com', 'status' => AppointmentStatus::Confirmed, 'days_ago' => -3],
        ];

        foreach ($appointmentsData as $data) {
            $teacher = $userModelClass::where('email', $data['teacher'])->first();
            $student = $userModelClass::where('email', $data['student'])->first();

            if (! $teacher || ! $student) {
                continue;
            }

            $start = now()->subDays($data['days_ago'])->setTime(rand(9, 15), [0, 30][rand(0, 1)]);
            $end = (clone $start)->addHour();

            Appointment::firstOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'student_id' => $student->id,
                    'start_time' => $start,
                ],
                [
                    'end_time' => $end,
                    'status' => $data['status'],
                    'meet_url' => 'https://meet.incadev.com/'.uniqid(),
                ]
            );
        }

        $this->command->info('✅ Citas creadas con éxito');
        $this->command->info('');

        // -----------------------------------
        // 3. ForumSeeder
        // -----------------------------------
        $this->command->info('💬 Creando foros de comunidad...');

        $forumsData = [
            ['name' => 'Dudas Académicas', 'description' => 'Espacio para resolver dudas sobre cursos, materiales y contenidos académicos.'],
            ['name' => 'Desarrollo Profesional', 'description' => 'Comparte experiencias y consejos sobre certificaciones, carreras y networking.'],
            ['name' => 'Soporte Técnico', 'description' => 'Ayuda con problemas técnicos de la plataforma, acceso y herramientas.'],
            ['name' => 'Proyectos y Colaboración', 'description' => 'Busca colaboradores para proyectos, hackathons y trabajos en equipo.'],
            ['name' => 'Bienestar Estudiantil', 'description' => 'Espacio seguro para hablar sobre balance vida-estudio, salud mental y apoyo mutuo.'],
        ];

        $forums = [];
        foreach ($forumsData as $forumData) {
            $forums[] = Forum::firstOrCreate(['name' => $forumData['name']], $forumData);
        }

        $this->command->info('✅ Foros creados exitosamente!');
        $this->command->info('');

        // -----------------------------------
        // 4. ThreadSeeder
        // -----------------------------------
        $this->command->info('📝 Creando hilos de discusión...');

        $allUsers = $userModelClass::whereIn('email', array_merge($teacherEmails, $studentEmails))->get();

        $threadsData = [
            // Foro: Dudas Académicas
            ['forum' => 'Dudas Académicas', 'user' => 'liliana@incadev.com', 'title' => '¿Cómo empezar con Machine Learning?', 'body' => 'Hola, estoy interesada en aprender Machine Learning pero no sé por dónde empezar. ¿Alguien me puede recomendar recursos o un roadmap? Tengo conocimientos básicos de Python.'],
            ['forum' => 'Dudas Académicas', 'user' => 'javier@incadev.com', 'title' => 'Diferencias entre AWS y Azure', 'body' => 'Estoy preparándome para certificaciones en la nube. ¿Cuáles son las principales diferencias entre AWS y Azure? ¿Cuál recomiendan aprender primero?'],

            // Foro: Desarrollo Profesional
            ['forum' => 'Desarrollo Profesional', 'user' => 'pedro@incadev.com', 'title' => 'Mi experiencia con la certificación PMP', 'body' => 'Acabo de aprobar el examen PMP después de 3 meses de preparación. Si alguien tiene dudas sobre el proceso, con gusto ayudo. La clave está en hacer muchos simulacros.'],
            ['forum' => 'Desarrollo Profesional', 'user' => 'sofia@incadev.com', 'title' => 'Tips para networking en tecnología', 'body' => 'Quiero ampliar mi red de contactos en el área de Data Science. ¿Qué estrategias han usado ustedes para hacer networking efectivo? ¿LinkedIn, eventos, comunidades?'],

            // Foro: Soporte Técnico
            ['forum' => 'Soporte Técnico', 'user' => 'valentina@incadev.com', 'title' => 'No puedo acceder a los materiales del curso', 'body' => 'Desde ayer no puedo descargar los PDFs de la sección de recursos. Me aparece un error 404. ¿A alguien más le pasa?'],

            // Foro: Proyectos y Colaboración
            ['forum' => 'Proyectos y Colaboración', 'user' => 'dante@incadev.com', 'title' => 'Busco colaboradores para proyecto de IA', 'body' => 'Estoy desarrollando un proyecto de visión por computadora para análisis de cultivos agrícolas. Busco personas con experiencia en Python, TensorFlow y procesamiento de imágenes. El proyecto es open source.'],
            ['forum' => 'Proyectos y Colaboración', 'user' => 'antonio@incadev.com', 'title' => 'Hackathon de Ciberseguridad - ¿Quién se anima?', 'body' => 'Hay un hackathon de ciberseguridad el próximo mes. Estoy armando un equipo. Necesitamos alguien con conocimientos en pentesting y análisis forense. ¿Interesados?'],

            // Foro: Bienestar Estudiantil
            ['forum' => 'Bienestar Estudiantil', 'user' => 'liliana@incadev.com', 'title' => 'Cómo manejar el estrés durante los exámenes', 'body' => 'Últimamente me siento muy estresada con todos los exámenes y proyectos. ¿Alguien tiene consejos para manejar mejor el tiempo y la ansiedad?'],
            ['forum' => 'Bienestar Estudiantil', 'user' => 'ana@incadev.com', 'title' => 'Importancia del descanso en el aprendizaje', 'body' => 'Como docente, quiero recordarles que descansar es parte fundamental del proceso de aprendizaje. No se sobrecarguen. Un cerebro descansado aprende mejor. ¿Qué técnicas usan para desconectar?'],
        ];

        $threads = [];
        foreach ($threadsData as $threadData) {
            $forum = Forum::where('name', $threadData['forum'])->first();
            $user = $userModelClass::where('email', $threadData['user'])->first();

            if (! $forum || ! $user) {
                continue;
            }

            $thread = Thread::firstOrCreate(
                [
                    'forum_id' => $forum->id,
                    'user_id' => $user->id,
                    'title' => $threadData['title'],
                ],
                ['body' => $threadData['body']]
            );

            $threads[] = $thread;
        }

        $this->command->info('✅ Hilos creados exitosamente!');
        $this->command->info('');

        // -----------------------------------
        // 5. CommentSeeder
        // -----------------------------------
        $this->command->info('💭 Creando comentarios...');

        // Comentarios para hilos específicos
        $commentsData = [
            // Thread: ¿Cómo empezar con Machine Learning?
            ['thread_title' => '¿Cómo empezar con Machine Learning?', 'user' => 'ana@incadev.com', 'parent' => null, 'body' => 'Te recomiendo empezar con el curso de Andrew Ng en Coursera. Es excelente para fundamentos. Luego practica con Kaggle.'],
            ['thread_title' => '¿Cómo empezar con Machine Learning?', 'user' => 'sofia@incadev.com', 'parent' => null, 'body' => 'Yo empecé con Python for Data Science. Necesitas estar cómoda con NumPy, Pandas y Matplotlib antes de ML.'],
            ['thread_title' => '¿Cómo empezar con Machine Learning?', 'user' => 'liliana@incadev.com', 'parent' => 1, 'body' => '¡Gracias Ana! Ya me inscribí en el curso de Coursera. ¿Cuánto tiempo me tomará completarlo?'],

            // Thread: Mi experiencia con la certificación PMP
            ['thread_title' => 'Mi experiencia con la certificación PMP', 'user' => 'dante@incadev.com', 'parent' => null, 'body' => 'Felicitaciones Pedro! ¿Qué recursos de estudio usaste? Estoy pensando en certificarme también.'],
            ['thread_title' => 'Mi experiencia con la certificación PMP', 'user' => 'pedro@incadev.com', 'parent' => 4, 'body' => 'Usé el PMBOK Guide, el curso de Joseph Phillips en Udemy y la app de PrepCast. Los simulacros son oro.'],
            ['thread_title' => 'Mi experiencia con la certificación PMP', 'user' => 'javier@incadev.com', 'parent' => null, 'body' => '¿Cuánto invertiste en total entre curso, examen y materiales?'],

            // Thread: No puedo acceder a los materiales del curso
            ['thread_title' => 'No puedo acceder a los materiales del curso', 'user' => 'antonio@incadev.com', 'parent' => null, 'body' => 'Valentina, ya reporté el problema al equipo técnico. Deberían solucionarlo en las próximas horas. Disculpa las molestias.'],
            ['thread_title' => 'No puedo acceder a los materiales del curso', 'user' => 'valentina@incadev.com', 'parent' => 7, 'body' => 'Perfecto, gracias Antonio. Estaré pendiente.'],

            // Thread: Busco colaboradores para proyecto de IA
            ['thread_title' => 'Busco colaboradores para proyecto de IA', 'user' => 'liliana@incadev.com', 'parent' => null, 'body' => '¡Me interesa! Tengo experiencia con TensorFlow y he trabajado en proyectos de clasificación de imágenes. ¿Cómo puedo unirme?'],
            ['thread_title' => 'Busco colaboradores para proyecto de IA', 'user' => 'sofia@incadev.com', 'parent' => null, 'body' => 'Qué interesante proyecto. ¿Tienen un repositorio en GitHub? Me gustaría ver el código actual.'],
            ['thread_title' => 'Busco colaboradores para proyecto de IA', 'user' => 'dante@incadev.com', 'parent' => 9, 'body' => 'Claro Liliana! Te envío el link del repo por mensaje privado. Bienvenida al equipo.'],

            // Thread: Cómo manejar el estrés durante los exámenes
            ['thread_title' => 'Cómo manejar el estrés durante los exámenes', 'user' => 'pedro@incadev.com', 'parent' => null, 'body' => 'A mí me funciona la técnica Pomodoro: 25 min de estudio, 5 de descanso. Y hacer ejercicio regularmente ayuda mucho.'],
            ['thread_title' => 'Cómo manejar el estrés durante los exámenes', 'user' => 'ana@incadev.com', 'parent' => null, 'body' => 'Excelente tema Liliana. También recomiendo técnicas de respiración y mindfulness. Hay apps gratuitas como Headspace.'],
            ['thread_title' => 'Cómo manejar el estrés durante los exámenes', 'user' => 'valentina@incadev.com', 'parent' => null, 'body' => 'Yo hago listas de tareas priorizadas. Me ayuda a no sentirme abrumada y ver mi progreso real.'],
            ['thread_title' => 'Cómo manejar el estrés durante los exámenes', 'user' => 'javier@incadev.com', 'parent' => null, 'body' => 'Lo peor que podemos hacer es compararnos con otros. Cada uno va a su ritmo. No se presionen tanto.'],

            // Thread: Importancia del descanso en el aprendizaje
            ['thread_title' => 'Importancia del descanso en el aprendizaje', 'user' => 'sofia@incadev.com', 'parent' => null, 'body' => 'Totalmente de acuerdo. Yo programo mis descansos como si fueran clases. Son sagrados.'],
            ['thread_title' => 'Importancia del descanso en el aprendizaje', 'user' => 'antonio@incadev.com', 'parent' => null, 'body' => 'Gran recordatorio Ana. También es importante dormir bien. El cerebro consolida lo aprendido durante el sueño.'],

            // Thread: Diferencias entre AWS y Azure
            ['thread_title' => 'Diferencias entre AWS y Azure', 'user' => 'antonio@incadev.com', 'parent' => null, 'body' => 'AWS tiene mayor cuota de mercado pero Azure se integra mejor con el ecosistema Microsoft. Depende de tu objetivo profesional.'],
            ['thread_title' => 'Diferencias entre AWS y Azure', 'user' => 'valentina@incadev.com', 'parent' => null, 'body' => 'Yo estoy estudiando AWS. Hay más recursos gratuitos y la comunidad es más grande.'],

            // Thread: Hackathon de Ciberseguridad
            ['thread_title' => 'Hackathon de Ciberseguridad - ¿Quién se anima?', 'user' => 'javier@incadev.com', 'parent' => null, 'body' => '¡Yo me uno! Tengo conocimientos en pentesting y análisis de vulnerabilidades.'],
            ['thread_title' => 'Hackathon de Ciberseguridad - ¿Quién se anima?', 'user' => 'antonio@incadev.com', 'parent' => 19, 'body' => 'Perfecto Javier! Te contacto para coordinar. Necesitamos un par más.'],
        ];

        $commentIdMap = [];
        foreach ($commentsData as $index => $commentData) {
            $thread = Thread::where('title', $commentData['thread_title'])->first();
            $user = $userModelClass::where('email', $commentData['user'])->first();

            if (! $thread || ! $user) {
                continue;
            }

            // Inicializamos parentId como null
            $parentId = null;
            $parentIndex = $commentData['parent'] ?? null;

            // Solo usamos el índice si no es null y es int o string
            /** @phpstan-ignore-next-line */
            if ($parentIndex !== null && (is_int($parentIndex) || is_string($parentIndex)) && array_key_exists($parentIndex, $commentIdMap)) {
                $parentId = $commentIdMap[$parentIndex];
            }

            $comment = Comment::firstOrCreate(
                [
                    'thread_id' => $thread->id,
                    'user_id' => $user->id,
                    'body' => $commentData['body'],
                ],
                ['parent_id' => $parentId]
            );

            // Guardamos el ID real en el mapa usando índice + 1 para que coincida con las referencias
            $commentIdMap[$index + 1] = $comment->id;
        }

        $this->command->info('✅ Comentarios creados exitosamente!');
        $this->command->info('');

        // -----------------------------------
        // 6. VoteSeeder
        // -----------------------------------
        $this->command->info('👍👎 Creando votos...');

        // Votos para threads (muchos likes, algunos dislikes, neutros)
        $threadVotesData = [
            // Thread muy popular (muchos likes)
            ['thread_title' => '¿Cómo empezar con Machine Learning?', 'voters' => ['ana@incadev.com' => 1, 'pedro@incadev.com' => 1, 'sofia@incadev.com' => 1, 'javier@incadev.com' => 1, 'antonio@incadev.com' => 1]],

            // Thread popular
            ['thread_title' => 'Mi experiencia con la certificación PMP', 'voters' => ['ana@incadev.com' => 1, 'dante@incadev.com' => 1, 'javier@incadev.com' => 1, 'sofia@incadev.com' => 1]],

            // Thread con opiniones divididas
            ['thread_title' => 'Tips para networking en tecnología', 'voters' => ['liliana@incadev.com' => 1, 'pedro@incadev.com' => 1, 'javier@incadev.com' => -1, 'antonio@incadev.com' => 1]],

            // Thread controversial (más dislikes)
            ['thread_title' => 'No puedo acceder a los materiales del curso', 'voters' => ['pedro@incadev.com' => -1, 'sofia@incadev.com' => -1, 'liliana@incadev.com' => 1]],

            // Thread neutral (pocos votos)
            ['thread_title' => 'Diferencias entre AWS y Azure', 'voters' => ['valentina@incadev.com' => 1, 'liliana@incadev.com' => 1]],

            // Thread muy valorado
            ['thread_title' => 'Importancia del descanso en el aprendizaje', 'voters' => ['liliana@incadev.com' => 1, 'pedro@incadev.com' => 1, 'sofia@incadev.com' => 1, 'javier@incadev.com' => 1, 'valentina@incadev.com' => 1, 'dante@incadev.com' => 1]],
        ];

        foreach ($threadVotesData as $data) {
            $thread = Thread::where('title', $data['thread_title'])->first();
            if (! $thread) {
                continue;
            }

            foreach ($data['voters'] as $email => $value) {
                $user = $userModelClass::where('email', $email)->first();
                if (! $user) {
                    continue;
                }

                Vote::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'votable_type' => Thread::class,
                        'votable_id' => $thread->id,
                    ],
                    ['value' => $value]
                );
            }
        }

        // Votos para comments
        $allComments = Comment::all();
        foreach ($allComments as $comment) {
            // Algunos comentarios tienen muchos votos, otros pocos
            $numVoters = rand(0, 4);
            $availableVoters = $allUsers->where('id', '!=', $comment->user_id)->random(min($numVoters, $allUsers->count() - 1));

            foreach ($availableVoters as $voter) {
                // 70% likes, 30% dislikes
                $value = (rand(1, 100) <= 70) ? 1 : -1;

                Vote::firstOrCreate(
                    [
                        'user_id' => $voter->id,
                        'votable_type' => Comment::class,
                        'votable_id' => $comment->id,
                    ],
                    ['value' => $value]
                );
            }
        }

        $this->command->info('✅ Votos creados exitosamente!');
        $this->command->info('');

        // -----------------------------------
        $this->command->info('🎉 SupportSeeder completado exitosamente!');
        $this->command->info('📊 Resumen:');
        $this->command->info('   - Disponibilidades: '.Availability::count());
        $this->command->info('   - Citas: '.Appointment::count());
        $this->command->info('   - Foros: '.Forum::count());
        $this->command->info('   - Hilos: '.Thread::count());
        $this->command->info('   - Comentarios: '.Comment::count());
        $this->command->info('   - Votos: '.Vote::count());
        $this->command->info('');
    }
}

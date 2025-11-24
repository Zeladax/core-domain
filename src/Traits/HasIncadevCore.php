<?php

namespace IncadevUns\CoreDomain\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use IncadevUns\CoreDomain\Models\Appointment;
use IncadevUns\CoreDomain\Models\Audit;
use IncadevUns\CoreDomain\Models\AuditAction;
use IncadevUns\CoreDomain\Models\Availability;
use IncadevUns\CoreDomain\Models\Certificate;
use IncadevUns\CoreDomain\Models\Comment;
use IncadevUns\CoreDomain\Models\Contract;
use IncadevUns\CoreDomain\Models\Conversation;
use IncadevUns\CoreDomain\Models\Enrollment;
use IncadevUns\CoreDomain\Models\Group;
use IncadevUns\CoreDomain\Models\Message;
use IncadevUns\CoreDomain\Models\StudentProfile;
use IncadevUns\CoreDomain\Models\SupportProfile;
use IncadevUns\CoreDomain\Models\SurveyResponse;
use IncadevUns\CoreDomain\Models\TeacherProfile;
use IncadevUns\CoreDomain\Models\TechAsset;
use IncadevUns\CoreDomain\Models\Thread;
use IncadevUns\CoreDomain\Models\Ticket;
use IncadevUns\CoreDomain\Models\TicketReply;
use IncadevUns\CoreDomain\Models\Vote;

trait HasIncadevCore
{
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function supportProfile(): HasOne
    {
        return $this->hasOne(SupportProfile::class);
    }

    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_users',
            'user_id',
            'conversation_id'
        )->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function groupsAsTeacher(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'group_teachers',
            'user_id',
            'group_id'
        )->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function groupsAsStudent(): HasManyThrough
    {
        return $this->hasManyThrough(Group::class, Enrollment::class, 'user_id', 'id', 'id', 'group_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function techAssets(): HasMany
    {
        return $this->hasMany(TechAsset::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function appointmentsAsTeacher(): HasMany
    {
        return $this->hasMany(Appointment::class, 'teacher_id');
    }

    public function appointmentsAsStudent(): HasMany
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function auditsAsAuditor(): HasMany
    {
        return $this->hasMany(Audit::class, 'auditor_id');
    }

    public function auditActionsResponsibleFor(): HasMany
    {
        return $this->hasMany(AuditAction::class, 'responsible_id');
    }
}

<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter {

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, array_keys($this->getAttributes()))){
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->{($this->getAttributes()[$attribute])}($attribute, $subject, $token);
    }

    protected function getAttributes(): array
    {
        return [
            'get_item' => 'isAdminOrOwner',
            'patch' => 'isAdminOrOwner'
        ];
    }

    public function isAdminOrOwner(string $attribute, $subject, TokenInterface $token): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        if ($subject === $token->getUser()) {
            return true;
        }
        return false;
    }

    protected function isAdmin(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
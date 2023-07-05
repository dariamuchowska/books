<?php
/**
 * User voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'VIEW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * Password change permission.
     *
     * @const string
     */
    public const PASSWORD = 'PASSWORD';

    /**
     * Security helper.
     */
    private Security $security;

    /**
     * OrderVoter constructor.
     *
     * @param Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::PASSWORD])
            && $subject instanceof User;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
            case self::VIEW:
                return $this->isOwnerOrAdmin($subject, $user);
            case self::PASSWORD:
                return $this->canChangePassword($subject, $user);
        }

        return false;
    }

    /**
     * Checks if user is Owner or Admin.
     *
     * @param object $subject Object
     * @param User   $user    User
     *
     * @return bool Result
     */
    private function isOwnerOrAdmin(object $subject, User $user): bool
    {
        return $subject->getId() === $user->getId() || in_array('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Chcecks if user can change password.
     *
     * @param object $subject Subject
     * @param User   $user    User
     *
     * @return bool Result
     */
    private function canChangePassword(object $subject, User $user): bool
    {
        return $subject->getId() === $user->getId();
    }
}

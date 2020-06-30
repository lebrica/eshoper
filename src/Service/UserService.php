<?php


namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $userRepository;
    private $passwordEncoder;
    private $codeGenerator;

    public function __construct(UserRepository $userRepository,
                                UserPasswordEncoderInterface $passwordEncoder,
                                CodeGenerator $codeGenerator)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->codeGenerator = $codeGenerator;
    }

    public function register($user): object
    {
        $password = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );

        $user->setPassword($password);
        $user->setConfirmationCode($this->codeGenerator->getConfirmationCode());

        return $this->userRepository->load($user);
    }

    public function confirmed($code)
    {
        $user = $this->userRepository->findOneBy(['confirmationCode' => $code]);
        if (!$user) {
            return false;
        }
        $user->setEnabled(true);
        $user->setConfirmationCode('');
        $this->userRepository->update();

        return $user;
    }

    public function changeRole($email, $role)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $user->setRoles($role);

        return $this->userRepository->update();
    }

    public function confirmCode(string $code): ?object
    {
        return $this->userRepository->findOneBy(['confirmationCode' => $code]);
    }
    /*
     * 1 - email exist, 0 - email not exist
     */
    public function checkExistEmail(string $email): int
    {
        return $this->userRepository->checkExistEmail($email);
    }
}
<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $repository;
    private $passwordEncoder;
    private $codeGenerator;

    public function __construct(UserRepository $repository,
                                UserPasswordEncoderInterface $passwordEncoder,
                                CodeGenerator $codeGenerator)
    {
        $this->repository = $repository;
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

        return $this->repository->save($user);
    }

    public function confirmCode(string $code): ?object
    {
        return $this->repository->findOneBy(['confirmationCode' => $code]);
    }



}
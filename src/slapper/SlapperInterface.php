<?php

namespace slapper;

interface SlapperInterface
{

    /** @return string[] */
    public function getCommands(): array;

    public function addCommand(string $command): void;

    public function hasCommand(string $command): bool;

    public function removeCommand(string $command): void;

    public function setSlapperVersion(string $version): void;

    public function getSlapperVersion(): string;
}
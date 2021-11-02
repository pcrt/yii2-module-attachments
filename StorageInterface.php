<?php
namespace pcrt\file;

interface StorageInterface
{
    public function setNext(StorageInterface $handler): StorageInterface;

    public function handle(object $request): ?object;

    public function put(object $request): ?object;

    public function rm(object $request): ?object;

    public function get(object $request): ?object;

    public function list(object $request): ?object;

    public function update(object $request): ?object;
}

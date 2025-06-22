<?php

namespace Akoryak\Components\Session;

interface SessionHandlerInterfaceLegacy {
    public function close(): bool;
    public function destroy(string $id): bool;
    public function gc(int $max_lifetime): int;
    public function open(string $path, string $name): bool;
    public function read(string $id): string;
    public function write(string $id, string $data): bool;
}

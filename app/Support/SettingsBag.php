<?php
namespace App\Support;
class SettingsBag {
    protected array $data = [];
    public function __construct($data = []) {
        if (is_object($data)) { $data = get_object_vars($data); }
        if (!is_array($data)) { $data = []; }
        $this->data = $data;
    }
    public function __get(string $name) { return $this->data[$name] ?? null; }
    public function __isset(string $name): bool { return true; } // avoid undefined property checks
    public function all(): array { return $this->data; }
}

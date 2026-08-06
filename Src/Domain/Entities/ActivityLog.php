<?php

namespace PLCTech\Domain\Entities;

class ActivityLog
{
        private ?int $id;
        private ?int $user_id;
        private ?string $username;
        private string $action;
        private ?string $description;
        private ?string $ip_address;
        private ?string $user_agent;
        private string $created_at;

        public function __construct(
                ?int $id,
                ?int $user_id,
                ?string $username,
                string $action,
                ?string $description = null,
                ?string $ip_address = null,
                ?string $user_agent = null,
                string $created_at = ''
        ) {
                $this->id = $id;
                $this->user_id = $user_id;
                $this->username = $username;
                $this->action = $action;
                $this->description = $description;
                $this->ip_address = $ip_address;
                $this->user_agent = $user_agent;
                $this->created_at = $created_at;
        }

        public function getId(): ?int
        {
                return $this->id;
        }

        public function getUserId(): ?int
        {
                return $this->user_id;
        }

        public function getUsername(): ?string
        {
                return $this->username;
        }

        public function getAction(): string
        {
                return $this->action;
        }

        public function getDescription(): ?string
        {
                return $this->description;
        }

        public function getIpAddress(): ?string
        {
                return $this->ip_address;
        }

        public function getUserAgent(): ?string
        {
                return $this->user_agent;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }
}

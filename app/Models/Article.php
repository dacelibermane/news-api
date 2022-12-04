<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private string $title;
    private string $description;
    private string $url;
    private ?string $imageUrl;

    public function __construct(string $title, string $description, string $url, ?string $imageUrl = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->imageUrl = $imageUrl;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

}
<?php

declare(strict_types=1);

namespace App\Controller;

use Parsedown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class BlogController extends AbstractController
{
    private string $contentDir;

    public function __construct(
        #[Autowire(param: 'kernel.project_dir')] string $projectDir,
    ) {
        $this->contentDir = $projectDir . '/content/blog';
    }

    #[Route(path: '/blog', name: 'blog_index')]
    public function index(): Response
    {
        $posts = $this->loadAllPosts();

        usort($posts, static fn (array $a, array $b) => strcmp($b['date'], $a['date']));

        return $this->render('app/blog/index.html.twig', ['posts' => $posts]);
    }

    #[Route(path: '/blog/{slug}', name: 'blog_post')]
    public function show(string $slug): Response
    {
        $post = $this->loadPost($slug);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('app/blog/show.html.twig', ['post' => $post]);
    }

    private function loadAllPosts(): array
    {
        $posts = [];

        foreach (glob($this->contentDir . '/*.md') as $file) {
            $post = $this->parseFile($file);
            if ($post !== null) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    private function loadPost(string $slug): ?array
    {
        foreach (glob($this->contentDir . '/*.md') as $file) {
            $post = $this->parseFile($file);
            if ($post !== null && $post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    private function parseFile(string $file): ?array
    {
        $content = file_get_contents($file);

        if ($content === false) {
            return null;
        }

        if (!str_starts_with($content, '---')) {
            return null;
        }

        $parts = explode('---', $content, 3);

        if (count($parts) < 3) {
            return null;
        }

        $frontmatter = $this->parseFrontmatter($parts[1]);
        $body = trim($parts[2]);

        $parsedown = new Parsedown();
        $parsedown->setSafeMode(false);

        return array_merge($frontmatter, [
            'html' => $parsedown->text($body),
            'excerpt' => $frontmatter['description'] ?? '',
        ]);
    }

    private function parseFrontmatter(string $raw): array
    {
        $data = [];

        foreach (explode("\n", trim($raw)) as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = explode(':', $line, 2);
            $data[trim($key)] = trim($value, ' "\'');
        }

        return $data;
    }
}

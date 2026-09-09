<?php

declare(strict_types=1);

namespace OxidSupport\Heartbeat\Tests\Unit\Shared;

use PHPUnit\Framework\TestCase;

/**
 * Guards the admin language files against the two ways they drift apart from the code:
 * an ident the code asks for that no language file defines (the admin then renders
 * "ERROR: Translation for ... not found!"), and a key nobody asks for any more.
 * See OXS-3383.
 */
class AdminTranslationsTest extends TestCase
{
    /** The language files the shop loads on this line, the admin theme is admin_twig. */
    private const LANGUAGE_FILES = [
        'views/admin_twig/de/module_options.php',
        'views/admin_twig/en/module_options.php',
    ];

    /** The sources that can ask for a translation on this line. */
    private const SOURCE_GLOBS = [
        'src/**/*.php',
        'views/twig/**/*.twig',
    ];

    /**
     * Idents that look like language keys but are not: GraphQL rights are named in the
     * same style and are checked by graphql-base, not translated.
     */
    private const NOT_TRANSLATIONS = [
        'OXSHEARTBEAT_TOKEN_INVALIDATE',
    ];

    public function testEveryIdentAskedForInTheCodeIsDefinedInEveryLanguageFile(): void
    {
        $used = $this->identsUsedInSources();
        $this->assertNotEmpty($used, 'no idents found, the source globs are wrong');

        foreach (self::LANGUAGE_FILES as $languageFile) {
            $defined = $this->identsDefinedIn($languageFile);
            $missing = array_values(array_diff($used, $defined));

            $this->assertSame(
                [],
                $missing,
                $languageFile . ' does not define: ' . implode(', ', $missing)
            );
        }
    }

    public function testEveryDefinedKeyIsStillAskedForSomewhere(): void
    {
        $used = $this->identsUsedInSources();

        foreach (self::LANGUAGE_FILES as $languageFile) {
            $unused = array_values(array_diff($this->identsDefinedIn($languageFile), $used));

            $this->assertSame(
                [],
                $unused,
                $languageFile . ' defines keys nobody asks for: ' . implode(', ', $unused)
            );
        }
    }

    public function testTheLanguageFilesDefineTheSameKeys(): void
    {
        $keysPerFile = [];
        foreach (self::LANGUAGE_FILES as $languageFile) {
            $keysPerFile[$languageFile] = $this->identsDefinedIn($languageFile);
        }

        $reference = array_shift($keysPerFile);
        foreach ($keysPerFile as $languageFile => $keys) {
            $this->assertSame(
                [],
                array_values(array_merge(array_diff($reference, $keys), array_diff($keys, $reference))),
                $languageFile . ' does not carry the same keys as the other language file'
            );
        }
    }

    /** @return string[] */
    private function identsUsedInSources(): array
    {
        $idents = [];

        foreach (self::SOURCE_GLOBS as $glob) {
            foreach ($this->glob($glob) as $file) {
                preg_match_all('/OXSHEARTBEAT_[A-Z0-9_]+/', (string) file_get_contents($file), $matches);
                $idents = array_merge($idents, $matches[0]);
            }
        }

        $idents = array_diff(array_unique($idents), self::NOT_TRANSLATIONS);
        sort($idents);

        return array_values($idents);
    }

    /** @return string[] */
    private function identsDefinedIn(string $relativePath): array
    {
        $file = $this->modulePath() . '/' . $relativePath;
        $this->assertFileExists($file);

        preg_match_all(
            "/'(OXSHEARTBEAT_[A-Z0-9_]+)'\s*=>/",
            (string) file_get_contents($file),
            $matches
        );

        $idents = array_unique($matches[1]);
        sort($idents);

        return array_values($idents);
    }

    /** @return string[] */
    private function glob(string $pattern): array
    {
        $base = $this->modulePath() . '/';

        if (strpos($pattern, '**') === false) {
            return glob($base . $pattern) ?: [];
        }

        [$prefix, $suffix] = explode('**', $pattern, 2);
        $files = [];
        $directories = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base . $prefix, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($directories as $file) {
            if (fnmatch('*' . $suffix, $file->getPathname())) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function modulePath(): string
    {
        return dirname(__DIR__, 3);
    }
}

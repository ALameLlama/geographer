<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

use function array_key_exists;
use function in_array;

/**
 * Class Russian
 */
final class Russian extends Base
{
    /**
     * @var string
     */
    protected $code = 'ru';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'из',
        'in' => 'в',
    ];

    private array $removableLetters = ['я', 'а', 'й', 'ь'];

    private array $replacementsFrom = [
        'subject' => [
            'л' => 'а', 'т' => 'а', 'к' => 'а', 'г' => 'а', 'м' => 'а', 'з' => 'а', 'ш' => 'а',
            'р' => 'а', 'с' => 'а', 'д' => 'а', 'н' => 'а', 'й' => 'я', 'я' => 'и', 'а' => 'ы',
            'ь' => 'и', 'в' => 'а', 'п' => 'а', 'ж' => 'а', 'ф' => 'а', 'х' => 'а',
        ],
        'adjective' => [
            'ая' => 'ой', 'ое' => 'ого', 'ий' => 'ого', 'ый' => 'ого',
        ],
    ];

    private array $replacementsIn = [
        'subject' => [
            'й' => 'е', 'л' => 'е', 'т' => 'е', 'г' => 'е', 'м' => 'е', 'з' => 'е', 'ш' => 'е',
            'р' => 'е', 'с' => 'е', 'д' => 'е', 'н' => 'е', 'а' => 'е', 'я' => 'и', 'к' => 'е',
            'ь' => 'и', 'в' => 'е', 'п' => 'е', 'ж' => 'е', 'ф' => 'е', 'х' => 'е',
        ],
        'adjective' => [
            'ая' => 'ой', 'ое' => 'ом', 'ий' => 'ом', 'ый' => 'ом',
        ],
    ];

    private array $vowels = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];

    /**
     * @param  string  $template
     * @return string
     */
    protected function inflictIn($template)
    {
        $output = $this->removeLastLetterIfNeeded($template);

        if ($this->isTwoWords($template)) {
            $output = $this->attemptToInflictFirstWordIn($output);
        }

        if (array_key_exists($this->getLastLetter($template), $this->replacementsIn['subject'])) {
            $output .= $this->replacementsIn['subject'][$this->getLastLetter($template)];
        }

        return $output;
    }

    /**
     * @param  string  $template
     * @return string
     */
    protected function inflictFrom($template)
    {
        $output = $this->removeLastLetterIfNeeded($template);

        if ($this->isTwoWords($template)) {
            $output = $this->attemptToInflictFirstWordFrom($output);
        }

        if (array_key_exists($this->getLastLetter($template), $this->replacementsFrom['subject'])) {
            $output .= $this->replacementsFrom['subject'][$this->getLastLetter($template)];
        }

        return $output;
    }

    /**
     * @param  string  $result
     * @return string
     */
    protected function getPreposition($form, $result = null)
    {
        $preposition = $this->defaultPrepositions[$form];

        if ($result && $form === 'in' && in_array(mb_strtolower(mb_substr($result, 0, 1)), ['в', 'ф']) &&
            ! $this->isVowel(mb_substr($result, 1, 1))) {
            $preposition .= 'о';
        }

        return $preposition;
    }

    /**
     * @return string
     */
    private function removeLastLetterIfNeeded($template)
    {
        if (in_array($this->getLastLetter($template), $this->removableLetters)) {
            return $this->removeLastLetter($template);
        }

        return $template;
    }

    private function attemptToInflictFirstWordIn($string): string
    {
        [$first, $second] = explode(' ', $string);

        if (array_key_exists($this->getLastLetter($first, 2), $this->replacementsIn['adjective'])) {
            $first = $this->removeLastLetter($first, 2) . $this->replacementsIn['adjective'][$this->getLastLetter($first, 2)];
        }

        if (mb_strtolower($first) === 'республика') {
            $first = 'Республике';
        }
        if (mb_strtolower($first) === 'область') {
            $first = 'Области';
        }
        if (mb_strtolower($first) === 'округ') {
            $first = 'Округе';
        }

        return $first . ' ' . $second;
    }

    private function attemptToInflictFirstWordFrom($string): string
    {
        [$first, $second] = explode(' ', $string);

        if (array_key_exists($this->getLastLetter($first, 2), $this->replacementsFrom['adjective'])) {
            $first = $this->removeLastLetter($first, 2) . $this->replacementsFrom['adjective'][$this->getLastLetter($first, 2)];
        }

        if (mb_strtolower($first) === 'республика') {
            $first = 'Республики';
        }
        if (mb_strtolower($first) === 'область') {
            $first = 'Области';
        }
        if (mb_strtolower($first) === 'округ') {
            $first = 'Округа';
        }

        return $first . ' ' . $second;
    }

    private function isTwoWords($string): bool
    {
        return mb_substr_count($string, ' ') === 1;
    }

    private function getLastLetter($string, int $count = 1): string
    {
        return mb_substr($string, mb_strlen($string) - $count);
    }

    private function removeLastLetter($string, int $count = 1): string
    {
        return mb_substr($string, 0, mb_strlen($string) - $count);
    }

    private function isVowel(string $character): bool
    {
        return in_array(mb_strtolower($character), $this->vowels);
    }
}

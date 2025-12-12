<?php

namespace App\Helpers;

class VariantHelper
{
    /**
     * Color code to readable name mapping
     */
    private static array $colorMap = [
        '#000000' => 'Black',
        '#FFFFFF' => 'White',
        '#FF0000' => 'Red',
        '#00FF00' => 'Green',
        '#0000FF' => 'Blue',
        '#FFFF00' => 'Yellow',
        '#FF00FF' => 'Magenta',
        '#00FFFF' => 'Cyan',
        '#FFA500' => 'Orange',
        '#800080' => 'Purple',
        '#FFC0CB' => 'Pink',
        '#A52A2A' => 'Brown',
        '#808080' => 'Gray',
        '#C0C0C0' => 'Silver',
        '#FFD700' => 'Gold',
        '#8B0000' => 'Dark Red',
        '#006400' => 'Dark Green',
        '#00008B' => 'Dark Blue',
        '#FF4500' => 'Orange Red',
        '#4B0082' => 'Indigo',
        '#FF1493' => 'Deep Pink',
        '#00CED1' => 'Dark Turquoise',
        '#228B22' => 'Forest Green',
        '#DC143C' => 'Crimson',
        '#FF6347' => 'Tomato',
        '#40E0D0' => 'Turquoise',
        '#EE82EE' => 'Violet',
        '#F0E68C' => 'Khaki',
        '#DDA0DD' => 'Plum',
        '#98D8C8' => 'Mint',
        '#F7DC6F' => 'Light Yellow',
        '#BB8FCE' => 'Lavender',
        '#85C1E2' => 'Sky Blue',
        '#F8C471' => 'Peach',
        '#82E0AA' => 'Light Green',
        '#F1948A' => 'Salmon',
        '#AED6F1' => 'Light Blue',
        '#F9E79F' => 'Beige',
        '#D5DBDB' => 'Light Gray',
        '#34495E' => 'Dark Gray',
        '#2C3E50' => 'Navy',
        '#E74C3C' => 'Bright Red',
        '#27AE60' => 'Emerald',
        '#3498DB' => 'Bright Blue',
    ];

    /**
     * Parse variant name into color and size parts
     *
     * @param string|null $variantName
     * @return array{color: string|null, size: string|null}
     */
    public static function parseVariant(?string $variantName): array
    {
        $color = null;
        $size = null;

        if (!$variantName || $variantName === 'Default') {
            return compact('color', 'size');
        }

        $variantParts = explode(' - ', $variantName);

        if (count($variantParts) >= 2) {
            $color = trim($variantParts[0]);
            $size = trim($variantParts[1]);
        } elseif (count($variantParts) === 1) {
            // If only one part, assume it's color (could be improved with better logic)
            $color = trim($variantParts[0]);
        }

        return compact('color', 'size');
    }

    /**
     * Convert color code to readable name
     *
     * @param string|null $colorCode
     * @return string
     */
    public static function getColorName(?string $colorCode): string
    {
        if (!$colorCode) {
            return 'N/A';
        }

        $colorCode = trim($colorCode);

        // Check if it's a hex color code
        if (str_starts_with($colorCode, '#')) {
            return self::$colorMap[$colorCode] ?? ucfirst(strtolower($colorCode));
        }

        // Check if it's already in the color map (case-insensitive)
        $normalized = strtolower($colorCode);
        foreach (self::$colorMap as $hex => $name) {
            if (strtolower($name) === $normalized) {
                return $name;
            }
        }

        // If it's already a color name, return formatted
        return ucfirst(strtolower($colorCode));
    }

    /**
     * Get color hex code for display
     *
     * @param string|null $color
     * @return string
     */
    public static function getColorHex(?string $color): string
    {
        if (!$color) {
            return '#808080'; // Default gray
        }

        $color = trim($color);

        // If it's already a hex code, return it
        if (str_starts_with($color, '#')) {
            return $color;
        }

        // Try to find hex code from color name
        $normalized = strtolower($color);
        foreach (self::$colorMap as $hex => $name) {
            if (strtolower($name) === $normalized) {
                return $hex;
            }
        }

        // Common color name mappings
        $nameToHex = [
            'black' => '#000000',
            'white' => '#FFFFFF',
            'red' => '#FF0000',
            'green' => '#00FF00',
            'blue' => '#0000FF',
            'yellow' => '#FFFF00',
            'orange' => '#FFA500',
            'purple' => '#800080',
            'pink' => '#FFC0CB',
            'brown' => '#A52A2A',
            'gray' => '#808080',
            'grey' => '#808080',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
        ];

        return $nameToHex[$normalized] ?? '#808080';
    }

    /**
     * Format variant for display
     *
     * @param string|null $variantName
     * @return string
     */
    public static function formatVariant(?string $variantName): string
    {
        if (!$variantName || $variantName === 'Default') {
            return 'Default';
        }

        ['color' => $color, 'size' => $size] = self::parseVariant($variantName);

        $parts = [];

        if ($color) {
            $parts[] = self::getColorName($color);
        }

        if ($size) {
            $parts[] = $size;
        }

        return implode(' - ', $parts) ?: $variantName;
    }
}


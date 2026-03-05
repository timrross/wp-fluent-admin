<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a media library chooser field.
 *
 * Outputs a hidden input for the attachment ID, an image preview, and
 * "Select Image" / "Remove" buttons.
 *
 * The caller must call MediaField::enqueueAssets() during admin_enqueue_scripts.
 */
class MediaField extends Field
{
    /**
     * Enqueue the WordPress media assets required by this field.
     * Call this in your admin_enqueue_scripts hook.
     *
     * @return void
     */
    public static function enqueueAssets(): void
    {
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $attachmentId = (int) $this->value;
        $previewUrl = '';

        // In a WP context, generate the image preview URL
        if ($attachmentId > 0 && function_exists('wp_get_attachment_image_url')) {
            $url = wp_get_attachment_image_url($attachmentId, 'thumbnail');
            $previewUrl = is_string($url) ? $url : '';
        }

        $previewStyle = $previewUrl !== '' ? '' : ' style="display:none;"';
        $previewSrc = Escape::url($previewUrl);
        $attachmentIdEscaped = Escape::attr((string) $attachmentId);

        $html = '<div class="fluent-admin-media-field" data-field-id="' . $id . '">';
        $html .= '<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . $attachmentIdEscaped . '" />';
        $html .= '<div class="fluent-admin-media-preview"' . $previewStyle . '>';
        $html .= '<img src="' . $previewSrc . '" alt="" style="max-width:150px;max-height:150px;" />';
        $html .= '</div>';
        $html .= '<button type="button" class="button fluent-admin-media-select">Select Image</button> ';
        $removeStyle = $attachmentId > 0 ? '' : ' style="display:none;"';
        $html .= '<button type="button" class="button fluent-admin-media-remove"'
            . $removeStyle
            . '>Remove</button>';
        $html .= '</div>';

        return $html;
    }
}

<?php

namespace Del\Form\Renderer\Field;

use Del\Form\Field\FieldInterface;
use Del\Form\Field\FileUpload;
use DOMElement;
use DOMNode;
use DOMText;
use InvalidArgumentException;

class FileUploadRender extends AbstractFieldRender
{

    /**
     * @param FieldInterface $field
     * @param DOMElement $element
     * @return DOMNode
     */
    public function renderBlock(FieldInterface $field, DOMElement $element): DOMElement
    {
        // Make sure the FieldInterface is actually a Radio
        if (!$field instanceof FileUpload) {
            throw new InvalidArgumentException('Must be a Del\Form\Field\FileUpload');
        }

        $div = $this->getDom()->createElement('div');
        $script = $this->getDom()->createElement('script');
        $style = $this->getDom()->createElement('style');
        $style->setAttribute('type', 'text/css');
        $style->appendChild(new DOMText($this->getStyle() ?? ''));
        $input = $this->getDom()->createElement('input');
        $span = $this->getDom()->createElement('span');
        $innerSpan = $this->getDom()->createElement('span');
        $text = new DOMText($field->getLabel() ?? '');

        $innerSpan->setAttribute('class', 'btn btn-primary btn-file');
        $innerSpan->appendChild($text);
        $innerSpan->appendChild($element);

        $span->setAttribute('class', 'input-group-btn');
        $span->appendChild($innerSpan);

        $input->setAttribute('type', 'text');
        $input->setAttribute('class', 'form-control');
        $input->setAttribute('readonly', 'readonly');

        $script->setAttribute('type', 'text/javascript');
        $code = new DOMText($this->getJavascript() ?? '');
        $script->appendChild($code);

        $div->setAttribute('class', 'input-group');
        $div->appendChild($span);
        $div->appendChild($input);
        $div->appendChild($style);
        $div->appendChild($script);

        return $div;
    }

    /**
     * @return string
     */
    private function getJavascript()
    {
        return <<<END
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });
END;

    }

    private function getStyle()
    {
        return <<<END
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    input[readonly] {
        background-color: white !important;
        cursor: text !important;
    }
END;

    }
}

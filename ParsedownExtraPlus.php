<?php

#
#
# Parsedown Extra Plus
# https://github.com/adjmpwgt/parsedown-extra-plus
#
# (c) Emanuil Rusev
# http://erusev.com
#
# (c) yasuhito kouchi
# https://adjmpw.com
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

class ParsedownExtraPlus extends ParsedownExtra
{

    # config

    public $blockCodeClassFormat = 'language-%s';

    public $blockPreClassHighlight = 'prettyprint';

    const version = '0.0.1';

    public function __construct()
    {
        if (version_compare(parent::version, '0.8.1') < 0) {
            throw new Exception('ParsedownExtraPlus requires a later version of ParsedownExtra');
        }
        parent::__construct();
    }

    protected function blockFencedCode($Line)
    {
        $Block = parent::blockFencedCode($Line);

        if (isset($Block['element']['name']) && $Block['element']['name'] == 'pre' && isset($Block['element']['text']['name']) && $Block['element']['text']['name'] == 'code') {
            if (isset($Block['element']['text']['attributes']['class'])) {
                if (strpos($Block['element']['text']['attributes']['class'], 'mermaid') === false) {
                    $Block['element']['attributes']['class'] = $this->blockPreClassHighlight;
                } else {
                    $Block['element']['text']['attributes']['class'] = str_replace(sprintf($this->blockCodeClassFormat, 'mermaid'), 'mermaid', $Block['element']['text']['attributes']['class']);
                }
            }
        }
        return $Block;
    }

    protected function createAnchorFragment($str)
    {
        $signs = array(
        '.', ';', '/', '?', ':', '@', '&', '=', '+', '$', ',', '%', '#', '\\', '\'',
        '|', '`', '^', '"', '<', '>', ')', '(', '}', '{', ']', '[',
        );
        $str = str_replace(' ', '-', $str);
        $str = str_replace($signs, '', $str);
        return $str;
    }

    protected function blockHeader($Line)
    {
        $Block = parent::blockHeader($Line);
        $text = $Block['element']['text'];

        // Github
        $html = '<a id="%s" class="anchor" href="#%s" aria-hidden="true">';
        $html .= '<span class="octicon octicon-link"></span></a>%s';

        $fragment = 'user-content-' . $this->createAnchorFragment($text);

        $Block['element']['text'] = sprintf($html, $fragment, $fragment, $text);

        return $Block;
    }
}

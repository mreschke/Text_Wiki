<?php

/**
 *
 * Parse for URLS in the source text.
 *
 * raw       -- http://example.com
 * no descr. -- [[http://example.com]]
 * described -- [[http://example.com|Example Description]]
 *
 * When rendering a URL token, this will convert URLs pointing to a .gif,
 * .jpg, or .png image into an inline <img /> tag (for the 'xhtml'
 * format).
 *
 * @category Text
 *
 * @package Text_Wiki
 *
 * @author Michele Tomaiuolo <tomamic@yahoo.it>
 *
 * @license LGPL
 *
 * @version $Id$
 *
 */

class Text_Wiki_Parse_Url extends Text_Wiki_Parse {

    /**
     *
     * Constructor.  Overrides the Text_Wiki_Parse constructor so that we
     * can set the $regex property dynamically (we need to include the
     * Text_Wiki $delim character).
     *
     * @param object &$obj The calling "parent" Text_Wiki object.
     *
     * @param string $name The token name to use for this rule.
     *
     */

    function Text_Wiki_Parse_Url(&$obj)
    {
        parent::Text_Wiki_Parse($obj);
        $this->regex = '/(\[\[((?:http:\/\/|https:\/\/|ftp:\/\/|gopher:\/\/|news:\/\/|mailto:|\/)[^\|\]\n ]*)(\|([^\]\n]*))?\]\]|((http:\/\/|https:\/\/|ftp:\/\/|gopher:\/\/|news:\/\/|mailto:)[^\'\"\n ' . $this->wiki->delim . ']*[A-Za-z0-9\/\?\=\&\~\_]))/';
    }


    /**
     *
     * Generates a replacement for the matched text.
     *
     * Token options are:
     *
     * 'href' => the URL link href portion
     *
     * 'text' => the displayed text of the URL link
     *
     * @access public
     *
     * @param array &$matches The array of matches from parse().
     *
     * @return string A token to be used as a placeholder
     * in the source text for the preformatted text.
     *
     */

    function process(&$matches)
    {
        $href = trim($matches[2]);
        $text = trim($matches[4]);
        $rawurl = $matches[5];
        if (! $href) $href = $rawurl;
        if (! $text) $text = $href;

        if (! strlen($text) || $text == $href) {
            return $this->wiki->addToken(
                $this->rule,
                array(
                    'href' => $href
                )
            );
        } else {
            return $this->wiki->addToken(
                $this->rule,
                array(
                    'type' => 'start',
                    'href' => $href,
                    'text' => $text
                )
            ) . $text .
            $this->wiki->addToken(
                $this->rule,
                array(
                    'type' => 'end',
                    'href' => $href,
                    'text' => $text
                )
            );
        }
    }

}
?>
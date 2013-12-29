<?php
/**
 * Created by PhpStorm.
 * User: Chekkan
 * Date: 29/12/13
 * Time: 19:52
 */

class FormHelperTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider textDataProvider
     */
    public function testTextMethodReturnExpected($params, $expected) {
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function textDataProvider() {
        return array(
            [
                null,
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>"
            ],
            array(
                "title",
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"title\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array(),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("invalid" => ""),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "title"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"title\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("id" => "Title"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" id=\"Title\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("label" => "Foo"),
                "<div class=\"form-group\">\n\r\t<label class=\"control-label\">Foo</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("placeholder" => "Bar"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Bar\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("value" => "Foo"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" value=\"Foo\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "foo", "id" => "Bar"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" id=\"Bar\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "foo", "placeholder" => "Bar"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" placeholder=\"Bar\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "foo", "label" => "Bar"),
                "<div class=\"form-group\">\n\r\t<label class=\"control-label\">Bar</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("id" => "Foo", "placeholder" => "Bar"),
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" id=\"Foo\" placeholder=\"Bar\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("id" => "Foo", "label" => "Bar"),
                "<div class=\"form-group\">\n\r\t<label for=\"Foo\" class=\"control-label\">Bar</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" id=\"Foo\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "foo", "label" => "Bar", "id" => "Foo"),
                "<div class=\"form-group\">\n\r\t<label for=\"Foo\" class=\"control-label\">Bar</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" id=\"Foo\" />\n\r\t</div>\n\r</div>"
            ),
            array(
                array("name" => "foo", "label" => "Bar", "id" => "Foo", "placeholder" => "Foo", "value" => "Bar"),
                "<div class=\"form-group\">\n\r\t<label for=\"Foo\" class=\"control-label\">Bar</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" id=\"Foo\" value=\"Bar\" placeholder=\"Foo\" />\n\r\t</div>\n\r</div>"
            )
        );
    }

}
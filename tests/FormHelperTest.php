<?php
/**
 * Created by PhpStorm.
 * User: Chekkan
 * Date: 29/12/13
 * Time: 19:52
 */

class FormHelperTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider startDataProvider
     */
    public function testStartMethodReturnExpected($params, $expected) {
        $sut = new FormHelper();
        $actual = $sut->start($params);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider endDataProvider
     */
    public function testEndMethodReturnExpected($params, $expected) {
        $sut = new FormHelper();
        $actual = $sut->end($params);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider endHorizontalFormDataProvider
     */
    public function testEndMethodReturnExpectedForHorizontalForm($params, $expected) {
        $expected = "\n\r\t<div class=\"form-group\">\n\r\t\t<div class=\"col-sm-offset-2 col-sm-10\">\n\r\t\t\t<input type=\"submit\" class=\"btn btn-default\" value=\"bar\" />\n\r\t\t</div>\n\r\t</div>\n\r</form>";
        $sut = new FormHelper();
        $sut->start(array("class" => "form-horizontal"));
        $actual = $sut->end("bar");
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider textDataProvider
     */
    public function testTextMethodReturnExpected($params, $expected) {
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dateDataProvider
     */
    public function testDateMethodReturnExpected($params, $expected) {
        $sut = new FormHelper();
        $actual = $sut->date($params);
        $this->assertEquals($expected, $actual);
    }

    public function textDataProvider() {
        return array(
            array(
                null,
                "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>"
            ),
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

    public function dateDataProvider() {
       return array(
           array(
               null,
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               "",
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               "foo",
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" name=\"foo\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("invalid" => ""),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("name" => "foo"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" name=\"foo\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("id" => "foo"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" id=\"foo\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("placeholder" => "foo"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" placeholder=\"foo\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("value" => "foo"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" value=\"foo\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("label"=>"foo"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<label class=\"control-label\">foo</label>\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
                array("name" => "foo", "id" => "bar"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" name=\"foo\" id=\"bar\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("label" => "foo", "id" => "bar"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<label for=\"bar\" class=\"control-label\">foo</label>\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" id=\"bar\" />\n\r\t\t</div>\n\r\t</div>"
           ),
           array(
               array("label" => "foo", "name" => "bar", "id" => "baz", "value" => "cat", "placeholder" => "daz"),
               "\n\r\t<div class=\"form-group\">\n\r\t\t<label for=\"baz\" class=\"control-label\">foo</label>\n\r\t\t<div>\n\r\t\t\t<input type=\"date\" class=\"form-control\" name=\"bar\" id=\"baz\" value=\"cat\" placeholder=\"daz\" />\n\r\t\t</div>\n\r\t</div>"
           )
       );
    }

    public function startDataProvider() {
        return array(
            array(
                null,
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">"
            ),
            array(
                "self",
                "<form action=\"self\" method=\"post\" role=\"form\">"
            ),
            array(
                array("invalid" => ""),
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">"
            ),
            array(
                array("action" => "self"),
                "<form action=\"self\" method=\"post\" role=\"form\">"
            ),
            array(
                array("method" => "foo"),
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"foo\" role=\"form\">"
            ),
            array(
                array("class" => "foo"),
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\" class=\"foo\">"
            ),
            array(
                array("action" => "foo", "method" => "bar", "class" => "baz"),
                "<form action=\"foo\" method=\"bar\" role=\"form\" class=\"baz\">"
            ),
            array(
                array("heading" => "foo"),
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">\n\r\t<h2>foo</h2>"
            )
        );
    }

    public function endDataProvider() {
        return array(
            array(
                null,
                "\n\r</form>"
            ),
            array(
                "Foo",
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" value=\"Foo\" />\n\r\t</div>\n\r</form>"
            ),
            array(
                array("value"=>"Foo"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" value=\"Foo\" />\n\r\t</div>\n\r</form>"
            ),
            array(
                array("invalid"=>""),
                "\n\r</form>"
            ),
            array(
                "",
                "\n\r</form>"
            ),
            array(
                array("name"=>"foo"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" name=\"foo\" />\n\r\t</div>\n\r</form>"
            ),
            array(
                array("id"=>"foo"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" id=\"foo\" />\n\r\t</div>\n\r</form>"
            ),
            array(
                array("value"=>"foo", "name"=>"bar"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" name=\"bar\" value=\"foo\" />\n\r\t</div>\n\r</form>"
            ),
            array(
                array("value"=>"foo", "name"=>"bar", "id"=>"baz"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<input type=\"submit\" class=\"btn btn-default\" name=\"bar\" value=\"foo\" id=\"baz\" />\n\r\t</div>\n\r</form>"
            )
        );
    }

    public function endHorizontalFormDataProvider() {
        return array(
            array(
                "bar",
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div class=\"col-sm-offset-2 col-sm-10\">\n\r\t\t\t<input type=\"submit\" class=\"btn btn-default\" value=\"bar\" />\n\r\t\t</div>\n\r\t</div>\n\r</form>"
            ),
            array(
                array("value" => "bar"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div class=\"col-sm-offset-2 col-sm-10\">\n\r\t\t\t<input type=\"submit\" class=\"btn btn-default\" value=\"bar\" />\n\r\t\t</div>\n\r\t</div>\n\r</form>"
            )
        );
    }
}
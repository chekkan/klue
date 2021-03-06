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
     * @dataProvider dateDataProvider
     */
    public function testTextMethodReturnExpected($params, $expected) {
        $expected = str_replace("date", "text", $expected);
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    //TODO: What if label wasn't set for $params?
    public function testTextMethodReturnExpectedForHorizontalFormFormat() {
        $expected = "\n\r\t<div class=\"form-group\">\n\r\t\t<label class=\"control-label col-sm-2\">foo</label>\n\r\t\t<div class=\"col-sm-10\">\n\r\t\t\t<input type=\"text\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>";
        $sut = new FormHelper();
        $sut->start(array("class" => "form-horizontal"));
        $actual = $sut->text(array("label" => "foo"));
        $this->assertEquals($expected, $actual);
    }

    /** @dataProvider textWithErrorDataProvider */
    public function testTextMethodWithErrorMessagesReturnExpected($params, $expected) {
        $sut = new FormHelper(array("foo"=>"bar"));
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

    /**
     * @dataProvider dateDataProvider
     */
    public function testEmailMethodReturnExpected($params, $expected) {
        $expected = str_replace("date", "email", $expected);
        $sut = new FormHelper();
        $actual = $sut->email($params);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dateDataProvider
     */
    public function testPasswordMethodReturnExpected($params, $expected) {
        $expected = str_replace("date", "password", $expected);
        $sut = new FormHelper();
        $actual = $sut->password($params);
        $this->assertEquals($expected, $actual);
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
                "\n\r<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">"
            ),
            array(
                "self",
                "\n\r<form action=\"self\" method=\"post\" role=\"form\">"
            ),
            array(
                array("invalid" => ""),
                "\n\r<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">"
            ),
            array(
                array("action" => "self"),
                "\n\r<form action=\"self\" method=\"post\" role=\"form\">"
            ),
            array(
                array("method" => "foo"),
                "\n\r<form action=\"{$_SERVER['PHP_SELF']}\" method=\"foo\" role=\"form\">"
            ),
            array(
                array("class" => "foo"),
                "\n\r<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\" class=\"foo\">"
            ),
            array(
                array("action" => "foo", "method" => "bar", "class" => "baz"),
                "\n\r<form action=\"foo\" method=\"bar\" role=\"form\" class=\"baz\">"
            ),
            array(
                array("heading" => "foo"),
                "\n\r<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\" role=\"form\">\n\r\t<h2>foo</h2>"
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

    public function textWithErrorDataProvider() {
        return array(
            array(
                null,
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"text\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
            ),
            array(
                array(),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"text\" class=\"form-control\" />\n\r\t\t</div>\n\r\t</div>"
            ),
            array(
                array("name"=>"foo"),
                "\n\r\t<div class=\"form-group\">\n\r\t\t<div>\n\r\t\t\t<input type=\"text\" class=\"form-control\" name=\"foo\" />\n\r\t\t\t<p class=\"text-danger\">bar</p>\n\r\t\t</div>\n\r\t</div>"
            )
        );
    }
}
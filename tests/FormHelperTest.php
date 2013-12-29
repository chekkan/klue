<?php
/**
 * Created by PhpStorm.
 * User: HBabu
 * Date: 29/12/13
 * Time: 19:52
 */

class FormHelperTest extends PHPUnit_Framework_TestCase {

    public function testTextMethodReturnsExpectedResultForEmptyParams() {
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text();
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedResultForPassingInStringForParams() {
        $params = "title";
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"{$params}\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedResultForPassingInAnEmptyArray() {
        $params = array();
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedForPassingInArrayWithInvalidAttribute() {
        $params = array("invalid" => "");
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedForPassingInArrayWithNameAttribute() {
        $params = array("name" => "title");
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" name=\"{$params['name']}\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedForPassingInArrayWithIdAttribute() {
        $params = array("id" => "Title");
        $expected = "<div class=\"form-group\">\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" id=\"{$params['id']}\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }

    public function testTextMethodReturnExpectedForPassingInArrayWithLabelAttribute() {
        $params = array("label" => "Title");
        $expected = "<div class=\"form-group\">\n\r\t<label class=\"control-label\">{$params['label']}</label>\n\r\t<div>\n\r\t\t<input type=\"text\" class=\"form-control\" />\n\r\t</div>\n\r</div>";
        $sut = new FormHelper();
        $actual = $sut->text($params);
        $this->assertEquals($expected, $actual);
    }
}
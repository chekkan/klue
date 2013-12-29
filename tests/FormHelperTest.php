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

}
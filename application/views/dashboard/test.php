<?php
$this->load->helper('download');

$data = 'Here is some text!';
$name = 'mytext.txt';

force_download($name, $data);
echo $news_item;
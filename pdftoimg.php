<?php
 // create Imagick object
 $imagick = new Imagick();
 // Reads image from PDF
 $imagick->readImage('sales_invoice_18040002.pdf');
 // Writes an image or image sequence Example- converted-0.jpg, converted-1.jpg
 $imagick->writeImages('converted.jpg', false);
?> 
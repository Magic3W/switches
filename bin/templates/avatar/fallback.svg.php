<?php 

current_context()->response->getHeaders()->contentType('image/svg+xml');

?><?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg
   xmlns="http://www.w3.org/2000/svg"
   width="100px"
   height="100px"
   viewBox="0 0 100 100"
   version="1.1">
  <g
     id="layer1">
    <rect
		 style="fill: <?= $color ?>"
       id="rect30"
       width="100"
       height="100"
       x="0"
       y="0"/>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:50px;line-height:1;font-family:sans-serif;fill:#ffffff;fill-opacity:1;stroke:none;stroke-width:4.78074"
       x="50"
       y="50"
       dominant-baseline="central"
       text-anchor="middle"
       id="text24"><tspan
         style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;-inkscape-font-specification:'-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, Open Sans, Helvetica Neue, sans-serif';fill:#ffffff;stroke-width:0"
         id="tspan26"><?= $character ?></tspan></text>
  </g>
</svg>
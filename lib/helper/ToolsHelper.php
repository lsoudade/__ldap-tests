<?php

function ls_var_dump($a)
{
  ob_start(); 
  
  $style   = ' style="border:solid 1px #000000;border-collapse:collapse;margin:5px;padding:5px;"';
  $styleth = substr($style, 0, strlen($style)-1) . 'background:#F6F6F6;"';
  
  if ( is_array($a) )
  {
    ?><table<?php echo $style; ?>>
      <?php foreach($a as $i => $v) : ?>
        <tr>
            <th<?php echo $styleth; ?>><?php echo $i; ?></th>
            
            <?php if ( !is_array($v) ) : ?>
                <td<?php echo $style; ?>><?php echo $v; ?></td>
            <?php else : ?>
                <td<?php echo $style; ?>><?php ls_var_dump($v); ?></td>
            <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </table><?php 
  }
  else
  {
    ?><table<?php echo $style; ?>>
        <tr>
          <td<?php echo $style; ?>><?php echo $a; ?></td>
        </tr>
    </table><?php
  }
  
  $content = ob_get_contents();
  ob_end_clean();

  echo $content;
}
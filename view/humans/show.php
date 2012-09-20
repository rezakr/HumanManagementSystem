<div>
    <p><a href ="/humans/show/<?=$param['result']->id?>/"><?=$param['result']->fname?> <?=$param['result']->lname?> </a></p> <a href ="/humans/edit/<?=$param['result']->id?>/">edit</a>
    <p>father ID : <?=$param['result']->fatherID?>, mother ID <?=$param['result']->motherID ?> </p>
    <p>Date of Birth : <?=$param['result']->dateOfBirth?>
    <?php if ($param['result']->gender){echo "He";}else{echo "She";}?> is <?php
    if($param['result']->dead) echo 'dead'; else echo 'alive';?></p>
    <div><a href ="/humans/show/<?=$param['result']->id?>/sons/">Sons</a> 
    <a href ="/humans/show/<?=$param['result']->id?>/daughters">Daughters</a> 
    <a href ="/humans/show/<?=$param['result']->id?>/children">Children</a> 
    <a href ="/humans/show/<?=$param['result']->id?>/father">Father</a> 
    <a href ="/humans/show/<?=$param['result']->id?>/mother">Mother</a></div>
    <div><a href ="/humans/show/<?=$param['result']->id?>/oldestBrother">Oldest Brother</a>
    <a href ="/humans/show/<?=$param['result']->id?>/oldestSister">Oldest Sister</a>
    <a href ="/humans/show/<?=$param['result']->id?>/youngestBrother">Youngest Brother</a>
    <a href ="/humans/show/<?=$param['result']->id?>/youngestSister">Youngest Sister</a></div>
</div>
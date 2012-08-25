<div>
    <p><a href ="/index.php/show/<?=$param['human']->id?>/"><?=$param['human']->fname?> <?=$param['human']->lname?> </a></p> <a href ="/index.php/edit/<?=$param['human']->id?>/">edit</a>
    <p>father ID : <?=$param['human']->fatherID?>, mother ID <?=$param['human']->motherID ?> </p>
    <p>Date of Birth : <?=$param['human']->dateOfBirth?>
    <?if ($param['human']->gender){echo "He";}else{echo "She";}?> is <?
    if($param['human']->dead) echo 'dead'; else echo 'alive';?></p>
    <div><a href ="/index.php/show/<?=$param['human']->id?>/sons/">Sons</a> 
    <a href ="/index.php/show/<?=$param['human']->id?>/daughters">Daughters</a> 
    <a href ="/index.php/show/<?=$param['human']->id?>/children">Children</a> 
    <a href ="/index.php/show/<?=$param['human']->id?>/father">Father</a> 
    <a href ="/index.php/show/<?=$param['human']->id?>/mother">Mother</a></div>
    <div><a href ="/index.php/show/<?=$param['human']->id?>/oldestBrother">Oldest Brother</a>
    <a href ="/index.php/show/<?=$param['human']->id?>/oldestSister">Oldest Sister</a>
    <a href ="/index.php/show/<?=$param['human']->id?>/youngestBrother">Youngest Brother</a>
    <a href ="/index.php/show/<?=$param['human']->id?>/youngestSister">Youngest Sister</a></div>
</div>
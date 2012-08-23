<div>
    <p><a href ="/index.php/show/<?=$param->id?>/"><?=$param->fname?> <?=$param->lname?> </a></p> <a href ="/index.php/edit/<?=$param->id?>/">edit</a>
    <p>father ID : <?=$param->fatherID?>, mother ID <?=$param->motherID ?> </p>
    <p>Date of Birth : <?=$param->dateOfBirth?>
    <?if ($param->gender){echo "He";}else{echo "She";}?> is <?
    if($param->dead) echo 'dead'; else echo 'alive';?></p>
    <div><a href ="/index.php/show/<?=$param->id?>/sons/">Sons</a> 
    <a href ="/index.php/show/<?=$param->id?>/daughters">Daughters</a> 
    <a href ="/index.php/show/<?=$param->id?>/children">Children</a> 
    <a href ="/index.php/show/<?=$param->id?>/father">Father</a> 
    <a href ="/index.php/show/<?=$param->id?>/mother">Mother</a></div>
    <div><a href ="/index.php/show/<?=$param->id?>/oldestBrother">Oldest Brother</a>
    <a href ="/index.php/show/<?=$param->id?>/oldestSister">Oldest Sister</a>
    <a href ="/index.php/show/<?=$param->id?>/youngestBrother">Youngest Brother</a>
    <a href ="/index.php/show/<?=$param->id?>/youngestSister">Youngest Sister</a></div>
</div>
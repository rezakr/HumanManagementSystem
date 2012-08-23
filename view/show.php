<div>
    <p><a href ="/index.php/show/<?=$param->id?>/"><?=$param->fname?> </a>
    <?=$param->lname?></p> <a href ="/index.php/edit/<?=$param->id?>/">edit</a>
    <p>father ID : <?=$param->fatherID?>, mother ID <?=$param->motherID ?> </p>
    <p>Date of Birth : <?=$param->birthdate?>
    <?if ($param->gender){echo "He";}else{echo "She";}?> is <?
    if($param->dead) echo 'dead'; else echo 'alive';?></p>
    <div><a href ="/index.php/show/<?=$param->id?>/sons/">sons</a> 
    <a href ="/index.php/show/<?=$param->id?>/daughters">daughters</a> 
    <a href ="/index.php/show/<?=$param->id?>/children">children</a> 
    <a href ="/index.php/show/<?=$param->id?>/father">father</a> 
    <a href ="/index.php/show/<?=$param->id?>/mother">mother</a>
    <a href ="/index.php/show/<?=$param->id?>/oldestBrother">Oldest Brother</a></div>
</div>
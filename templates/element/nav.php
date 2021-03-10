    <?php 
    $a_name= $this->request->getParam('controller');
    $a_name= $this->request->getParam('action');
    //echo $a_name;
    //exit;
    ?>
    <nav class="navbar navbar-expand-lg navbar-light ">

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">

          <li class=<?= $a_name == 'home' ? 'bg-warning':'' ?>>
            <a class="nav-link" href=<?= $this->Url->Build(['controler'=>'Blogs','action'=>'home']) ?>>Home</a>
          </li>

          <li class=<?= $a_name == 'about' ? 'bg-warning':'' ?>>
            <a class="nav-link" href=<?= $this->Url->build(['controler'=>'Blogs','action'=>'about'])?> >About</a>
          </li>
          <li class=<?= $a_name == 'contect' ? 'bg-warning':'' ?>>
            <a class="nav-link" href=<?= $this->Url->build(['controler'=>'Blogs','action'=>'contect'])?> >Contact</a>
          </li>
        </ul>
      </div>

    </nav>
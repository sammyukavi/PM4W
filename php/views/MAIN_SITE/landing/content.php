<section id="promo" attr-title="Home" class="promo section offset-header has-pattern">
    <div class="container">
        <div class="row">
            <div class="overview col-md-8 col-sm-12 col-xs-12">
                <h2 class="title">
                    Pay Me 4 Water
                </h2>
                <ul class="summary">
                    <li>Keep track of sales</li>
                    <li>Keep track of expenses</li>
                    <li>Keep track of savings</li>
                    <li>Provide water users with timely reports</li>                            
                </ul>
                <div class="download-area">
                    <ul class="btn-group list-inline">                               
                        <li class="android-btn">
                            <a href="https://play.google.com/store/apps/details?id=com.eyeeza.apps.pm4w" target="_blank">Get it from Google Play</a>
                        </li>
                        <?php /* $App->con->where('published', 1);
                          $App->con->where('preferred', 1);
                          $App->con->join('files', 'id_file=file_id', 'LEFT');
                          $build = $App->con->getOne('app_builds');
                          if (!empty($build)) {
                          ?>
                          <li>
                          <a href="/attachment/<?php echo $build['file_name']; ?>" rel="noindex, nofollow" class="btn btn-success btn-lg">
                          Download
                          <span class="" style="display: block; font-style: italic;">
                          <strong>v</strong> <?php echo $build['build_version']; ?>, <?php echo $build['is_stable'] == 1 ? 'Stable' : 'Nightly build'; ?><?php echo empty($build['compatible_devices']) ? '' : ', Compatible Devices: ' . $build['compatible_devices']; ?>
                          </span>
                          </a>
                          </li>
                          <?php } */ ?>
                    </ul>                            
                </div>
            </div>


            <div class="phone android col-md-4 col-sm-12 col-xs-12">
                <div class="android-holder phone-holder">
                    <div class="android-holder-inner">
                        <div class="slider flexslider">
                            <ul class="slides">
                                <?php
                                for ($index = 1; $index <= 8; $index++) {
                                    ?>
                                    <li>
                                        <img src="/assets/images/android/shot-<?php echo $index; ?>.jpg"  alt="PM4W Sales dashboard" />
                                    </li>
                                    <?php
                                }
                                ?>                               
                            </ul>
                        </div>
                    </div>                  
                </div>                 
            </div>


        </div>
    </div>
</section>

<section id="features" attr-title="Features" class="features section">
    <div class="container">
        <div class="row">
            <h2 class="title text-center sr-only">Features</h2>
            <div class="item col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="icon">
                    <i class="fa fa-money"></i>                
                </div>
                <div class="content">
                    <h3 class="title">Sales Management</h3>
                    <p>Different users and role players are able to manage water sales easily.</p>  
                </div>               
            </div>
            <div class="item col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="icon">
                    <i class="fa fa-rocket"></i>                
                </div>
                <div class="content">
                    <h3 class="title">Expenses</h3>
                    <p>Different role players and users are able to keep track of water sources and expenses.</p>   
                </div>               
            </div>
            <div class="item col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="icon">
                    <i class="fa fa-users"></i>                
                </div>
                <div class="content">
                    <h3 class="title">Follow Up</h3>
                    <p>Caretakers and attendants are able to follow up on defaulters. </p> 
                </div>               
            </div>

        </div>

        <div class="row feature-row-last">
            <div class="item col-md-6 col-sm-6 col-xs-12 text-center">
                <div class="icon">
                    <i class="fa fa-line-chart"></i>                
                </div>
                <div class="content">
                    <h3 class="title">Reports</h3>
                    <p>Different role players are able to recive timely reports</p>   
                </div>              
            </div>

            <div class="item col-md-6 col-sm-6 col-xs-12 text-center">
                <div class="icon">
                    <i class="fa fa-comments"></i>                
                </div>
                <div class="content">
                    <h3 class="title">Communication</h3>
                    <p>Different role players are able to communicate using the app</p>  
                    <button class="modal-trigger btn btn-link" data-toggle="modal" data-target="#feature-modal-1">Find out more</button>
                </div>           
            </div>
        </div>
    </div>
</section>

<section id="story" attr-title="Story" class="story section has-pattern">
    <div class="container">
        <div class="row">
            <div class="content col-md-6 col-sm-6 col-xs-12 text-center">
                <h2 class="title">Story behind the app</h2>
                <p>Rural Africans have poor access to clean and safe water compared to other developing areas. Many Information and Communication Technology (ICT) interventions have been implemented to address the information gaps that hinder improved service delivery but have subsequently failed.</p>
                <p>PM4W is a research tool to support rural communities in the management of their water supplies under the Community based (water) management model. This research seeks to not only empower communities to participate in the design and development of ICT tools that meet their needs but also to show how technology design approaches can contribute to sustainability of community-based ICT interventions.</p>
            </div>
            <div class="team col-md-5 col-sm-5 col-md-offset-1 col-sm-offset-1 col-xs-12">
                <div class="row">
                    <div class="member col-md-12 text-center">
                        <img class="img-rounded" src="/assets/images/team/fiona.png" alt="" />
                        <p class="name">Fiona Ssozi-Mugarura</p>
                        <p class="title">Information Systems Analyst & ICT4D Researcher (Appropriate ICTs for Rural Water Management)</p>
                        <ul class="connect list-inline">
                            <li>
                                <a href="https://twitter.com/fssozi" target="_blank" >
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/in/fiona-ssozi-mugarura-562b8156" target="_blank" >
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>                                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials section">
    <div class="container">
        <div class="row">
            <h2 class="title text-center animated fadeInUp delayp1" style="opacity: 0;">Where can I get it?</h2>
            <div class="item col-md-12 col-sm-12">
                <div class="animated fadeInUp delayp3 text-center" style="opacity: 0;">
                    <p>PM4W is still a research project and is still limited to a few users. However the project is open source and is provided without any warranty or user support. The project is Licenced under <strong>MIT Licence</strong> and 
                        it's source can be downloaded from <a href="http://github.com/sammyukavi/pm4w" target="_blank">github</a>. The project might contain one or two bugs, some features might not be developed fully and others are missing so pulls and merges are welcome. If you do not want to build the project yourself you can 
                        get an unsigned build from the <a href="/downloads" title="Downloads">downloads</a> page.</p>
                </div>
            </div>                    
        </div> 
        <?php /* ?>
          <div class="row">
          <div class="item col-md-offset-4 col-sm-offset-4 col-md-4 col-sm-4">
          <div class="animated fadeInUp delayp3 text-center" style="opacity: 0;">
          <a class="btn btn-success" target="_blank" href="#">
          <img src="/assets/images/buttons/btn-google-play.png"/>
          </a>
          </div>
          </div>
          </div>
          <?php */ ?>
    </div>
</section>

<section id="contact-us" attr-title="Contact Us" class="contact section has-pattern">
    <div class="container">
        <div class="row text-center">
            <h2 class="title">We'd love to hear from you</h2>
            <div class="intro col-md-6 col-sm-12 col-xs-12 col-md-offset-3">
                <p>Do you have questions, a feature you'd like us to incorporate, other suggestions or you'd like to talk to us? We'd love your feedback. Leave us a message 
                    using the form below and we'll get back to you as soon as possible.</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="contact-form col-md-6 col-sm-12 col-xs-12 col-md-offset-3">                            
                <form class="form" method="post" action="">                
                    <div class="form-group name">
                        <label class="sr-only" for="name">Name</label>
                        <input id="name" name="name" type="text" class="form-control" placeholder="Name:" required="required">
                    </div>
                    <div class="form-group email">
                        <label class="sr-only" for="email">Email</label>
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email:" required="required">
                    </div>
                    <div class="form-group message">
                        <label class="sr-only" for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="6" placeholder="Message:" required="required"></textarea>
                    </div>
                    <div class="form-group text-center">
                        <div class="g-recaptcha" style="width: 100%;" data-sitekey="<?php echo $CONFIG['recaptcha_site_key']; ?>"></div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-theme">Send Message</button>
                </form>                 
            </div>
        </div>
        <div class="text-center">
            <ul class="social-icons list-inline">
                <li>
                    <a href="https://github.com/sammyukavi/pm4w" target="_blank" >
                        <i class="fa fa-github"></i>
                    </a>
                </li>                                       
            </ul>
        </div>
    </div>
</section>

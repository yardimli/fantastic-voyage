<!DOCTYPE html>
<html lang="en_US">
<head>
<script src="/assets/js/core/jquery.min.js"></script>

	<script>

		$(document).ready(function(){
			var logo_character= $('#logo_character');
			var logo_land= $('#logo_land');
			var logo_numbers1= $('#logo_numbers1');
			var logo_numbers2= $('#logo_numbers2');
			var logo_operator= $('#logo_operator');

			let orig_character = logo_character.css('transform'); // Getting original position
			let orig_land = logo_land.css('transform');
			let orig_numbers1 = logo_numbers1.css('transform');
			let orig_numbers2 = logo_numbers2.css('transform');
			let orig_operator = logo_operator.css('transform');

			var logo_div= $('.float-test');

			logo_div.on('mousemove',function(e){
				var cvalueX= (e.pageX * 1 / 80);
				var cvalueY= (e.pageY * -1 / 30);
				var lvalueX= (e.pageX * -1 / 60);
				var lvalueY= (e.pageY * -1 / 30);
				var n1valueX= (e.pageX * -1 / 80);
				var n2valueX= (e.pageX * 1 / 130);
				var nvalueY= (e.pageY * -1 / 50);
				var ovalueX= (e.pageX * -1 / 60);
				var ovalueY= (e.pageY * 1 / 60);
				logo_character.css('transform', 'translate3d('+cvalueX+'px,'+cvalueY+'px, 0)');
				logo_land.css('transform', 'translate3d('+lvalueX+'px,'+lvalueY+'px, 0)');
				logo_numbers1.css('transform', 'translate3d('+n1valueX+'px,'+nvalueY+'px, 0)');
				logo_numbers2.css('transform', 'translate3d('+n2valueX+'px,'+nvalueY+'px, 0)');
				logo_operator.css('transform', 'translate3d('+ovalueX+'px,'+ovalueY+'px, 0)');
			});

			logo_div.on('mouseleave',function(e){
				logo_character.css('transform', orig_character); // Moving back to original position
				logo_land.css('transform', orig_land);
				logo_numbers1.css('transform', orig_numbers1);
				logo_numbers2.css('transform', orig_numbers2);
				logo_operator.css('transform', orig_operator);
			});


			var hold_left= $('.floater-left');
			var hold_right= $('.floater-right');

			let orig_hold_left = hold_left.css('transform'); // Getting original position
			let orig_hold_right = hold_right.css('transform');

			var video_div= $('.video');
			video_div.on('mousemove',function(e){
				var lvalueX= (e.pageX * -1 / 100);
				var rvalueX= (e.pageX * 1 / 100);
				hold_left.css('transform', 'translate3d('+lvalueX+'px, 0, 0)');
				hold_right.css('transform', 'translate3d('+rvalueX+'px, 0, 0)');
			});

		});

	</script>
</head>
<body>
<div class="w-embed w-iframe w-script">
	<div class="page-wrapper-3">
		<nav fs-scrolldisable-element="smart-nav" class="nav_component">
			<div class="page-padding">
				<div class="container-large nav">
					<div class="container-large logo"><a href="/" aria-current="page"
					                                     class="logo-nav w-inline-block w--current"><img
								src="/assets/logos/new_logo.png"
								alt="" class="logo-coolxue"></a>
					</div>
					<div class="container-large nav-wrap">
						<div class="container-medium-2 nav-item">
							<div data-w-id="c58a6b3b-6ca0-4596-9980-a81388e33ade" class="nav-link-4 sub-item">
								<div class="container-large menu">
									<div>{{__('default.Teachers')}}</div>
									<img src="/assets/images/new_images/arrow-down.svg" alt="" class="icon-tiny margin-left"></div>
							</div>
							<div data-w-id="c58a6b3b-6ca0-4596-9980-a81388e33aee" class="nav-link-4 sub-item">
								<div class="container-large menu">
									<div>Parents</div>
									<img src="/assets/images/new_images/arrow-down.svg" alt="" class="icon-tiny margin-left"></div>
							</div>
							<a href="/premium" target="_blank" class="nav-link-4 sub-item mobile w-inline-block"><img
									src="/assets/logos/square_logo_120.png"
									alt="" class="icon-1x1-small margin-right no-bg">
								<div>{{__('default.Get Premium')}}</div>
							</a><a href="/contact" class="nav-link-4 hide w-inline-block">
								<div>Contact</div>
							</a><a href="#" data-w-id="c58a6b3b-6ca0-4596-9980-a81388e33b00"
							       class="nav-link-4 download hide-tablet w-inline-block"><img
									src="/assets/logos/square_logo_face.png"
									alt="" class="icon-small margin-right">
								<div>Download App</div>
							</a></div>
						<div class="container-small buttons hide-tablet"><a href="/login"
						                                                    class="button-login w-button">Teacher/Parent</a><a
								href="https://fantastic-voyage.com/" class="button-secondary-2 margin-left w-button">Play</a></div>
					</div>

				</div>
			</div>
		</nav>
		<main class="main-wrapper">
			<section class="section-hero">
				<div
					data-autoplay="true" data-loop="true" data-wf-ignore="true" data-beta-bgvideo-upgrade="false"
					class="bg-video w-background-video w-background-video-atom">
					<video id="b43cf433-332e-9441-4507-7f06db4bc54b-video" autoplay="" loop=""
					       style="background-image:url(&quot;/assets/images/new_images/poster.jpg&quot;)"
					       muted="" playsinline="" data-wf-ignore="true" data-object-fit="cover">
{{--						<source src="/assets/images/new_images/poster-00001.jpg" data-wf-ignore="true">--}}
						<source src="/assets/images/new_images/roll-transcode.mp4" data-wf-ignore="true">
						<source src="/assets/images/new_images/roll-transcode.webm" data-wf-ignore="true">
					</video>
				</div>
				<div class="page-padding">
					<div class="container-large hero"><h1 class="text-color-white break main-header">{{__('default.Learning can be seriously fun.')}}</h1>
						<div class="container-large hero-content-wrap show-desktop"><h2 class="text-color-white break hero">{{__('default.CoolXue transforms education with engagement. Unlock your confidence to learn.')}}</h2>
							<div class="container-large margin-top">
								<div class="text-size-medium text-color-white hero">{{__('default.Create free account as a:')}}</div>
								<div class="container-large button-wrap"><a href="/register"
								                                            class="button-hero margin-right orange w-button">{{__('default.Teacher')}}</a><a
										href="/register"
										class="button-hero-alt green w-button">{{__('default.Parent') }}</a></div>
							</div>
						</div>
						<div data-w-id="aa207384-558f-1b86-eb26-e3678d19aa18" class="float-test">
							<img id="logo_character" src="/assets/images/characters/Aspirant_Logo1.png" style="position: absolute; bottom:45px; right:-50px;">
							<img id="logo_land" src="/assets/images/characters/Aspirant_Logo2.png" style="position: absolute; bottom:22px; right:65px;">
							<img id="logo_operator" src="/assets/images/characters/Aspirant_Logo3.png" style="position: absolute; bottom:-45px; right:25px;">
							<img id="logo_numbers1" src="/assets/images/characters/Aspirant_Logo4.png" style="position: absolute; bottom:-77px; right:105px;">
							<img id="logo_numbers2" src="/assets/images/characters/Aspirant_Logo5.png" style="position: absolute; bottom:-77px; right:105px;">
						</div>
					</div>
				</div>
			</section>

			<section class="section-benefit main">
				<div class="page-padding">
					<div class="container-large benefit-main">
						<div id="grid_1_1_1" class="spacer-2"></div>
						<div id="grid_1_1_1" class="container-large benefit-card"><img
								src="/assets/images/new_images/Create%20More%20Engagement.svg"
								alt="" class="icon-1x1-xlarge margin-right">
							<div class="container-large benefit-content"><h3 class="headline">Foster a Love for Learning</h3>
								<p>Your students will have a blast learning at home and at school!</p></div>
						</div>
						<div class="grid_1_1_1 container-large benefit-card"><img
								src="/assets/images/new_images/Differentiate%20Learning.svg"
								alt="" class="icon-1x1-xlarge margin-right">
							<div class="container-large benefit-content"><h3 class="headline">Differentiate<br>Instruction</h3>
								<p>Automatically tailor learning to meet each student's needs.</p></div>
						</div>
						<div class="grid_1_1_1 spacer-2"></div>
						<div class="grid_1_1_1 container-large benefit-card"><img
								src="/assets/images/new_images/Close%20Gaps.svg"
								alt="" class="icon-1x1-xlarge margin-right">
							<div class="container-large benefit-content"><h3 class="headline">24/7 Free Access</h3>
								<p>Your students can keep the fun learning going from any internet-connected device.</p></div>
						</div>
						<div class="grid_1_1_1 container-large benefit-card"><img
								src="/assets/images/new_images/Real%20Time%20Reports.svg"
								alt="" class="icon-1x1-xlarge margin-right">
							<div class="container-large benefit-content"><h3 class="headline">Real-Time<br>Reports</h3>
								<p>Stay in the loop on students' learning progress with real-time reports!</p></div>
						</div>
					</div>
				</div>
			</section>

			<section class="section-video">
				<div class="page-padding">
					<div data-w-id="b43cf433-332e-9441-4507-7f06db4bc58d" class="container-large video"><h3
							class="heading-large text-color-white">Check out the game!</h3>
						<p class="text-color-white margin-top">Students can access CoolXue on any internet-connected device at school
							AND at home.</p>
						<div data-w-id="b43cf433-332e-9441-4507-7f06db4bc590" class="video-placeholder">
							<div style="padding-top:56.17021276595745%" class="video-quicklook w-video w-embed">
								<iframe class="embedly-embed"
								        src="//cdn.embedly.com/widgets/media.html?src=https%3A%2F%2Fwww.youtube.com%2Fembed%2FsCOoOB-Xbd0%3Ffeature%3Doembed&amp;display_name=YouTube&amp;url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DsCOoOB-Xbd0&amp;image=https%3A%2F%2Fi.ytimg.com%2Fvi%2FsCOoOB-Xbd0%2Fhqdefault.jpg&amp;key=96f1f04c5f4143bcb0f2e68c87d65feb&amp;type=text%2Fhtml&amp;schema=youtube"
								        scrolling="no" allowfullscreen="" title="A Quick Look at CoolXue Learning"></iframe>
							</div>
							<a href="#" data-w-id="b43cf433-332e-9441-4507-7f06db4bc592"
							   style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
							   class="icon-play w-inline-block w-lightbox" aria-label="open lightbox" aria-haspopup="dialog"><img
									src="/assets/images/new_images/icon-play.svg"
									alt=""></a><img
								src="/assets/images/new_images/video-placeholder.webp"
								style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
								alt="" class="video-thumb"><img
								src="/assets/images/characters/hold1.png" alt="" class="floater-left"><img
								src="/assets/images/characters/hold2.png" alt="" class="floater-right">
						</div>
						<a href="/login" class="button-secondary-2 with-shadow w-button">Log in to Play</a>
					</div>
				</div>
			</section>
			<section class="section-howitworks">
				<div class="page-padding">
					<div class="container-large howitworks"><h3 class="heading-large">Let's Get Started!</h3>
						<div class="grid-howitworks margin-top">
							<div class="grid_1_1_1 container-large steps"><img
									src="/assets/images/characters/register.png"
									alt="" class="image-steps margin-bottom">
								<h3 class="margin-bottom">1. Sign Up for Free</h3>
								<div class="container-large button-wrap align-center"><a
										href="/register"
										class="button-hero margin-right teacher w-button">{{__('default.Teacher')}}</a><a
										href="/register"
										class="button-hero-alt green w-button">{{__('default.Parent') }}</a></div>
							</div>
							<div class="grid_1_1_1 container-large steps"><img
									src="/assets/images/characters/login.png"
									alt="" class="image-steps margin-bottom">
								<h3 class="margin-bottom">2. Log Kids in</h3><a href="https://fantastic-voyage.com/" class="button-secondary-2 with-shadow blue w-button">Log in to Play</a>
								<div class="container-large app-download large"><a
										href="#" target="_blank"
										class="download-appstore w-inline-block"><img
											src="/assets/images/new_images/applestore.svg"
											alt="" class="icon-large appstore"></a></div>
							</div>
							<div class="grid_1_1_1 container-large steps"><img
									src="/assets/images/characters/together.png"
									alt="" class="image-steps margin-bottom">
								<h3 class="margin-bottom">3. Learn Together</h3>
								<p class="p-break small">Create assignments, track performance, and easily address learning gaps with
									automated reports.</p></div>
						</div>
					</div>
				</div>
			</section>
			<img src="/assets/images/new_images/wave-bg.webp"
			     alt="" class="bg-image-2">
			<section class="section-testimonial">
				<div class="page-padding">
					<div class="container-large testimonial"><h3 class="heading-large text-color-white margin-bottom">What's the
							Word?</h3>
						<div class="grid-testimonial margin-vertical-custom">
							<div class="grid_1_1_1 container-large testimony-card premium"><img
									src="/assets/images/characters/hang_left.png" alt=""
									class="grid_1_1_1 character-overlay premium left">
								<div class="container-large wrap"><p class="premium-testimony-text">“I enjoy that my students can
										practice
										the skills taught, or I can use the platform to introduce new skills. Great for independent learning
										and small groups.”</p></div>
								<div class="container-large testimony-wrap">
									<div class="container-large premium-testimony-quote">
										<div class="text-color-blue premium-testimony margin-right">Misty G.</div>
										<div class="text-color-blue premium-testimony">9/18/2022</div>
									</div>
									<div class="container-large premium-testimony-rating"><img
											src="/assets/images/new_images/stars.png"
											alt="" class="stars"></div>
								</div>
							</div>
							<div class="grid_1_1_1 container-large testimony-card premium">
								<div class="container-large wrap"><p class="premium-testimony-text">“My students love using CoolXue. It
										is
										exciting and I especially love that there are connected videos to go over the skills.”</p></div>
								<div class="container-large testimony-wrap">
									<div class="container-large premium-testimony-quote">
										<div class="text-color-blue premium-testimony margin-right">Michelle J.</div>
										<div class="text-color-blue premium-testimony">9/26/2022</div>
									</div>
									<div class="container-large premium-testimony-rating"><img
											src="/assets/images/new_images/stars.png"
											alt="" class="stars"></div>
								</div>
								<img
									src="/assets/images/characters/hang_middle.png"
									alt="" class="character-overlay premium middle"></div>
							<div class="grid_1_1_1 container-large testimony-card premium">
								<div class="container-large wrap"><p class="premium-testimony-text">“Both of my kids love this game.
										It’s
										been especially great for the child who struggles to believe they are good at math.”</p></div>
								<div class="container-large testimony-wrap">
									<div class="container-large premium-testimony-quote">
										<div class="text-color-blue premium-testimony margin-right">Kleineklein</div>
										<div class="text-color-blue premium-testimony">7/27/2022</div>
									</div>
									<div class="container-large premium-testimony-rating"><img
											src="/assets/images/new_images/apple-store.webp"
											alt=""><img
											src="/assets/images/new_images/stars.png"
											alt="" class="stars"></div>
								</div>
								<img
									src="/assets/images/characters/hang_right.png"
									alt="" class="character-overlay premium right"></div>
						</div>
			</section>

			<section class="section-learnmore">
				<div class="page-padding">
					<div class="container-large learnmore"><h3 class="heading-large">Learn more</h3>
						<div class="grid-learnmore margin-top">
							<div class="grid_1_1_1 container-large learn-more">
								<div class="container-large wrap">
									<div class="container-large image-cover margin-bottom"><img
											src="/assets/images/characters/teacher.png"
											alt="" class="image-learnmore"
											style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
									</div>
									<h3>For Teachers</h3>
									<p class="p-break medium">Time-saving tools, curriculum-aligned content, and the perfect balance of
										learning and fun for your students.</p></div>
								<a href="/teachers-page" class="button-secondary-2 margin-top w-button"
								   style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">Learn
									More</a></div>
							<div class="grid_1_1_1 container-large learn-more">
								<div class="container-large wrap">
									<div class="container-large image-cover margin-bottom"><img
											src="/assets/images/characters/parent.png"
											alt="" class="image-learnmore"
											style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
									</div>
									<h3>For Parents</h3>
									<p class="p-break medium">Healthy screen time that <br>helps your child <br>level up in math and ELA.&nbsp;<br>
									</p></div>
								<a href="/parents-page" class="button-secondary-2 margin-top w-button"
								   style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">Learn
									More</a></div>
							<div class="grid_1_1_1 container-large learn-more">
								<div class="container-large wrap">
									<div class="container-large image-cover margin-bottom"><img
											src="/assets/images/characters/blog.png"
											alt="" class="image-learnmore"
											style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
									</div>
									<h3>Blog &amp;&nbsp;Case Studies</h3>
									<p class="p-break medium">A proven learning tool to help kids&nbsp;stay engaged and improve
										performance.</p></div>
								<a href="/blog" class="button-secondary-2 margin-top w-button"
								   style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">Visit
									Blog</a></div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<footer class="footer_component">
			<div class="page-padding">
				<div class="container-large footer">
					<div class="line-2"></div>
					<div class="grid-footerlinks margin-vertical">
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">General</div>
							<a href="/" aria-current="page" class="footer-link-item w--current">Home</a><a href="/about"
							                                                                               class="footer-link-item">about</a><a href="/premium" class="footer-link-item text-style-bold">CoolXue Premium</a></div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Resources</div>
							<a href="/teachers-page" class="footer-link-item">Teacher page</a><a href="/parents-page"
							                                                                     class="footer-link-item">parent
								page</a><a href="/teaching-resources" class="footer-link-item">teaching resources</a></div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Support</div>
							<a href="#" class="footer-link-item">support center</a><a
								href="#FAQS" class="footer-link-item">FAQs</a><a href="/support"
							                                                                                         class="footer-link-item">getting
								set up</a><a href="/contact"
							                                                                             class="footer-link-item">Contact</a>
						</div>
						<div class="grid_1_1_1 container-large footer-links">
							<div class="heading-small text-color-blue margin-bottom">Legal</div>
							<a href="/terms-of-service" class="footer-link-item">terms of service</a><a href="/privacy-policy"
							                                                                            class="footer-link-item">privacy
								notice</a></div>
					</div>
					<div class="line-2"></div>
					<div class="copyright margin-top">Copyright 2023 © CoolXue Learning</div>
				</div>
			</div>
		</footer>
		{{--	<div class="banner-bottom">--}}
		{{--		<div class="text-color-white text-size-small">🛡️ CoolXue's got your back: Fully FERPA and COPPA compliant. <a--}}
		{{--				target="_blank" href="/safety-privacy" class="text-style-link text-color-white">Explore More Here</a>.--}}
		{{--		</div>--}}
		{{--	</div>--}}
	</div>


</body>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
	href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,500;0,700;1,100;1,500;1,700&display=swap"
	rel="stylesheet">

<style>

    body {
        margin: 0;
    }

    footer,
    main,
    nav,
    section {
        display: block;
    }

    video {
        vertical-align: baseline;
        display: inline-block;
    }

    a {
        background-color: rgba(0, 0, 0, 0);
    }

    a:active,
    a:hover {
        outline: 0;
    }

    strong {
        font-weight: bold;
    }

    h1 {
        margin: 0.67em 0;
        font-size: 2em;
    }

    img {
        border: 0;
    }

    svg:not(:root) {
        overflow: hidden;
    }

    * {
        box-sizing: border-box;
    }

    body {
        min-height: 100%;
        color: #333;
        background-color: #fff;
        margin: 0;
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
    }

    img {
        max-width: 100%;
        vertical-align: middle;
        display: inline-block;
    }

    .w-inline-block {
        max-width: 100%;
        display: inline-block;
    }

    .w-button {
        color: #fff;
        line-height: inherit;
        cursor: pointer;
        background-color: #3898ec;
        border: 0;
        border-radius: 0;
        padding: 9px 15px;
        text-decoration: none;
        display: inline-block;
    }

    h1,
    h2,
    h3 {
        margin-bottom: 10px;
        font-weight: bold;
    }

    h1 {
        margin-top: 20px;
        font-size: 38px;
        line-height: 44px;
    }

    h2 {
        margin-top: 20px;
        font-size: 32px;
        line-height: 36px;
    }

    h3 {
        margin-top: 20px;
        font-size: 24px;
        line-height: 30px;
    }

    p {
        margin-top: 0;
        margin-bottom: 10px;
    }

    blockquote {
        border-left: 5px solid #e2e2e2;
        margin: 0 0 10px;
        padding: 10px 20px;
        font-size: 18px;
        line-height: 22px;
    }

    .w-embed:before,
    .w-embed:after {
        content: " ";
        grid-area: 1 / 1 / 2 / 2;
        display: table;
    }

    .w-embed:after {
        clear: both;
    }

    .w-video {
        width: 100%;
        padding: 0;
        position: relative;
    }

    .w-video iframe {
        width: 100%;
        height: 100%;
        border: none;
        position: absolute;
        top: 0;
        left: 0;
    }

    .w-background-video {
        height: 500px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .w-background-video > video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -100;
        background-position: 50%;
        background-size: cover;
        margin: auto;
        position: absolute;
        top: -100%;
        bottom: -100%;
        left: -100%;
        right: -100%;
    }

    body {
        color: #474459;
        letter-spacing: -0.02em;
        font-family: Montserrat, sans-serif;
        font-size: 18px;
        font-weight: 500;
        line-height: 1.778em;
    }

    h1 {
        color: #0b091b;
        letter-spacing: -0.03em;
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.1;
    }

    h2 {
        color: #0b091b;
        letter-spacing: -0.03em;
        font-size: 1.75rem;
        font-weight: 500;
        line-height: 1.1;
    }

    h3 {
        color: #0b091b;
        letter-spacing: -0.03em;
        margin-top: 0;
        margin-bottom: 0;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.308em;
    }

    p {
        margin-bottom: 0;
    }

    a {
        color: #fb0f5a;
        font-weight: 500;
        line-height: 1.222em;
        text-decoration: none;
        transition: color 0.35s;
    }

    a:hover {
        color: #2991fe;
    }

    img {
        max-width: 100%;
    }

    strong {
        color: #0b091b;
        font-weight: 700;
    }

    blockquote {
        color: #fb0f5a;
        background-color: rgba(251, 15, 90, 0.04);
        border-left: 0 solid #000;
        border-radius: 25px;
        margin-bottom: 0;
        padding: 55px 62px 54px;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.385em;
    }

    .footer {
        background-color: #fff;
        border-top: 1px solid #ceccdc;
        padding-top: 35px;
        padding-bottom: 35px;
    }

    .column {
        justify-content: center;
        display: flex;
    }

    .floater-message {
        z-index: 999;
        color: #2d7ff9;
        background-color: #2d7ff9;
        justify-content: center;
        align-items: center;
        padding: 8px 24px;
        display: flex;
        position: fixed;
        bottom: 20px;
        left: 20px;
    }

    .floater-message.close-button {
        width: auto;
        height: auto;
        max-width: 1500px;
        grid-column-gap: 16px;
        grid-row-gap: 16px;
        text-align: left;
        border-radius: 11px;
        grid-template-rows: auto auto;
        grid-template-columns: 1fr 1fr;
        grid-auto-columns: 1fr;
        justify-content: center;
        margin-left: 0;
        display: none;
        position: fixed;
        bottom: 7px;
        left: 15px;
        right: auto;
        overflow: hidden;
    }

    .image-26 {
        order: -1;
    }

    .image-26.close-button.cookie {
        width: 50px;
        height: 50px;
        margin-left: 0;
        display: none;
    }

    .block-quote-4 {
        color: #fff;
        object-fit: fill;
        background-color: rgba(0, 0, 0, 0);
        padding: 0 0 0 10px;
    }

    .block-quote-4.close-button.wording {
        width: auto;
        height: auto;
        font-size: 13px;
        font-weight: 500;
        display: none;
    }

    .close-button {
        width: 40px;
        height: 40px;
        cursor: pointer;
        justify-content: center;
        align-items: center;
        margin-left: 10px;
        display: flex;
        position: relative;
    }

    .button-secondary-2 {
        color: #fff;
        text-align: center;
        background-color: #f9a242;
        border: 0.125rem solid #f9a242;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
        padding: 1.25rem 1.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        display: flex;
    }

    .button-secondary-2.with-shadow {
        transition: transform 0.2s ease-in-out;
        box-shadow: 7px 7px 5px 1px rgba(0, 0, 0, 0.25);
    }

    .button-secondary-2.with-shadow:hover {
        color: #fff;
        transform: scale(1.05);
    }

    .button-secondary-2.with-shadow.blue {
        background-color: #2991fe;
        background-image: url("/assets/images/new_images/boddle%20logo%20icon%203.webp");
        background-position: 10%;
        background-repeat: no-repeat;
        background-size: 15%;
        border-color: #2991fe;
        margin-bottom: 1rem;
        padding-left: 5rem;
    }

    .button-secondary-2.with-shadow.blue:hover {
        color: #fff;
    }

    .button-secondary-2.margin-top {
        width: 16rem;
        margin-top: 1rem;
        font-weight: 700;
        transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
    }

    .button-secondary-2.margin-top:hover {
        color: #fff;
        transform: scale(1.05);
        box-shadow: 7px 7px 5px 1px rgba(0, 0, 0, 0.25);
    }

    .button-secondary-2.margin-left {
        margin-left: 1rem;
        font-size: 1rem;
        transition: opacity 0.5s ease-in-out;
    }

    .button-secondary-2.margin-left:hover {
        color: #fff;
        background-color: rgba(249, 162, 66, 0.75);
    }

    .button-secondary-2.dropdown-menu {
        width: 10ch;
        max-width: 100%;
        margin-bottom: 0.5rem;
        margin-left: 0;
        padding: 0.95rem;
        transition: opacity 0.5s ease-in-out;
    }

    .button-secondary-2.dropdown-menu:hover {
        color: #fff;
        background-color: rgba(249, 162, 66, 0.75);
    }

    .hide {
        display: none;
    }

    .container-large {
        width: 100%;
        max-width: 90rem;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
        display: flex;
    }

    .container-large.menu {
        flex-direction: row;
        align-items: center;
    }

    .container-large.benefit-card {
        flex-direction: row;
        justify-content: space-between;
    }

    .container-large.footer-cta {
        flex-direction: row;
        padding-bottom: 3rem;
    }

    .container-large.sub-menu-link {
        z-index: 3;
        width: 150%;
        text-align: center;
        background-color: #fff;
        border-radius: 12px;
        flex-direction: column;
        padding: 1rem;
        display: none;
        position: absolute;
        top: 100%;
        box-shadow: 4px 4px 3px 1px rgba(0, 0, 0, 0.25);
    }

    .container-large.footer-links {
        flex-direction: column;
        align-items: flex-start;
    }

    .container-large.show-tablet.padding-custom1 {
        display: none;
    }

    .container-large.nav {
        height: 5rem;
        flex-direction: row;
        justify-content: space-between;
        display: flex;
    }

    .container-large.margin-top {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 2rem;
    }

    .container-large.footer {
        flex-direction: column;
        padding-top: 4rem;
        padding-bottom: 2rem;
    }

    .container-large.testimony-card {
        text-align: center;
        background-color: #fff;
        border-radius: 15px;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        transition: transform 0.35s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .container-large.testimony-card:hover {
        transform: scale(1.1);
    }

    .container-large.testimony-card.premium {
        position: relative;
    }

    .container-large.nav-wrap {
        width: 100%;
        flex-direction: row;
    }

    .container-large.benefit-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .container-large.howitworks {
        text-align: center;
        flex-direction: column;
        align-items: center;
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    .container-large.image-cover {
        border-radius: 15px;
        overflow: hidden;
    }

    .container-large.image-cover.margin-bottom {
        background-image: linear-gradient(#3c86e8, #4694f6);
        margin-bottom: 1.25rem;
    }

    .container-large.press {
        flex-direction: column;
        align-items: center;
    }

    .container-large.mobile-nav.show-tablet {
        display: none;
    }

    .container-large.hero-content-wrap.show-desktop {
        flex-direction: column;
        align-items: flex-start;
    }

    .container-large.learnmore {
        text-align: center;
        flex-direction: column;
        align-items: center;
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    .container-large.app-download {
        flex-direction: row;
        justify-content: center;
        margin-left: 0;
        margin-right: auto;
    }

    .container-large.app-download.large {
        flex-direction: column;
        justify-content: space-between;
        align-items: stretch;
    }

    .container-large.app-download.modal {
        flex-direction: column;
        align-items: center;
        margin-left: auto;
    }

    .container-large.learn-more {
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .container-large.hero {
        flex-direction: column;
        align-items: flex-start;
        padding: 7rem 0 5rem 2.5rem;
        position: relative;
    }

    .container-large.logo {
        width: auto;
        flex-direction: row;
        align-items: stretch;
    }

    .container-large.button-wrap {
        flex-direction: row;
        margin-top: 1.5rem;
    }

    .container-large.button-wrap.align-center {
        justify-content: center;
        margin-top: 0;
    }

    .container-large.featured {
        align-items: center;
    }

    .container-large.video {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    .container-large.column {
        width: 50%;
        flex-direction: column;
        margin-left: 0;
        margin-right: 0;
    }

    .container-large.column.margin-bottom {
        flex-direction: column;
    }

    .container-large.steps {
        flex-direction: column;
        align-items: center;
    }

    .container-large.testimonial {
        flex-direction: column;
        align-items: center;
        padding-top: 2rem;
        padding-bottom: 3rem;
        position: relative;
    }

    .container-large.socials {
        flex-direction: row;
        justify-content: center;
        margin-left: 0;
        margin-right: 0;
    }

    .container-large.wrap {
        flex-direction: column;
        align-items: center;
    }

    .container-large.download {
        z-index: 99;
        width: 100vw;
        height: 100vh;
        max-width: none;
        opacity: 0.95;
        background-color: #fff;
        justify-content: center;
        align-items: center;
        display: none;
        position: fixed;
        top: 0%;
        bottom: 0%;
        left: 0%;
        right: 0%;
    }

    .container-large.appstore {
        flex-direction: row;
        justify-content: center;
    }

    .container-large.premium-testimony-quote {
        justify-content: flex-start;
        margin-top: 1rem;
    }

    .container-large.premium-testimony-rating {
        margin-top: 1rem;
    }

    .container-large.testimony-wrap {
        flex-direction: column;
        align-items: flex-start;
    }

    .container-large.benefit-main {
        grid-column-gap: 1rem;
        grid-row-gap: 2rem;
        grid-template-rows: auto auto;
        grid-template-columns: 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        display: grid;
    }

    .icon-large {
        height: 4rem;
    }

    .icon-large.appstore {
        width: 11.9rem;
    }

    .icon-large.googlestore {
        width: 11.8rem;
    }

    .text-size-medium {
        font-size: 1.25rem;
    }

    .text-size-medium.text-color-white.hero {
        font-size: 1.25rem;
        line-height: 1.1;
    }

    .text-size-medium.margin-bottom {
        text-align: center;
        margin-bottom: 2rem;
    }

    .margin-left {
        margin-top: 0;
        margin-bottom: 0;
        margin-right: 0;
    }

    .margin-bottom {
        margin: 0 0 1rem;
    }

    .margin-right {
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 0;
    }

    .button-hero-alt {
        color: #43b328;
        text-align: center;
        background-color: #fff;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
        padding: 1.25rem 1.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        transition: transform 0.2s ease-in-out;
        box-shadow: 7px 7px 5px 1px rgba(0, 0, 0, 0.25);
    }

    .button-hero-alt:hover {
        transform: scale(1.05);
    }

    .button-hero-alt.green:hover {
        color: #43b328;
    }

    .heading-small {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .heading-small.text-color-blue {
        color: #3c86e8;
        text-transform: capitalize;
    }

    .heading-small.text-color-blue.margin-bottom {
        margin-bottom: 0.25rem;
    }

    .heading-medium {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .margin-top {
        margin-bottom: 0;
        margin-left: 0;
        margin-right: 0;
    }

    .heading-large {
        color: #2991fe;
        text-align: center;
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .heading-large.margin-bottom {
        margin-bottom: 2rem;
    }

    .heading-large.text-color-white {
        font-size: 3rem;
    }

    .icon-small {
        height: 2rem;
    }

    .icon-small.margin-right {
        border-radius: 8px;
        margin-right: 0.5rem;
    }

    .main-wrapper {
        overflow: hidden;
    }

    .container-medium-2 {
        width: 100%;
        max-width: 64rem;
        margin-left: auto;
        margin-right: auto;
    }

    .container-medium-2.nav-item {
        max-width: 45rem;
        justify-content: space-between;
        margin-right: 0;
        display: flex;
    }

    .align-center {
        margin-left: auto;
        margin-right: auto;
    }

    .margin-vertical {
        margin-left: 0;
        margin-right: 0;
    }

    .icon-1x1-small {
        width: 2rem;
        height: 2rem;
    }

    .icon-1x1-small.margin-right {
        background-color: #916ef7;
        border-radius: 8px;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
        display: flex;
    }

    .icon-1x1-small.margin-right.no-bg {
        width: 1.5rem;
        height: 1.5rem;
        background-color: rgba(0, 0, 0, 0);
        margin-right: 0.5rem;
    }

    .show-tablet {
        display: none;
    }

    .text-style-link {
        text-decoration: underline;
    }

    .text-style-link.text-color-white {
        font-weight: 400;
    }

    .text-style-link.text-color-white:hover {
        color: #ffce22;
    }

    .button-hero {
        color: #f9a242;
        text-align: center;
        background-color: #fff;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
        padding: 1.25rem 1.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        box-shadow: 7px 7px 5px 1px rgba(0, 0, 0, 0.25);
    }

    .button-hero.margin-right {
        margin-right: 1rem;
        transition: transform 0.2s ease-in-out;
    }

    .button-hero.margin-right:hover {
        transform: scale(1.05);
    }

    .button-hero.margin-right.teacher:hover,
    .button-hero.margin-right.orange:hover {
        color: #f9a242;
    }

    .icon-1x1-medium {
        width: 3rem;
        height: 3rem;
    }

    .icon-1x1-medium.x {
        border: 1px solid #000;
        border-radius: 10px;
        padding: 12px;
    }

    .page-padding {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .container-small {
        width: 100%;
        max-width: 48rem;
        margin-left: auto;
        margin-right: auto;
    }

    .container-small.buttons {
        max-width: 17rem;
        justify-content: flex-end;
        align-items: stretch;
        margin-right: 0;
        padding-top: 1rem;
        padding-bottom: 1rem;
        display: flex;
    }

    .text-size-small {
        font-size: 0.875rem;
        font-weight: 700;
    }

    .page-wrapper-3 {
        margin-left: auto;
        margin-right: auto;
    }

    .video-quicklook {
        border-radius: 12px;
        display: none;
    }

    .section-hero {
        min-height: 680px;
        background-image: url("/assets/images/new_images/white-wave-bg.svg"),
        linear-gradient(112.5deg, #3c86e8 40%, rgba(37, 49, 101, 0.65) 65%);
        background-position: 50% 101%, 0 0;
        background-repeat: no-repeat, repeat;
        background-size: contain, auto;
        position: relative;
    }

    .text-color-white {
        color: #fff;
        font-size: 20px;
    }

    .text-color-white.break {
        width: 30rem;
    }

    .text-color-white.break.main-header {
        font-size: 3rem;
    }

    .text-color-white.break.hero {
        font-size: 1.75rem;
    }

    .text-color-white.margin-top {
        text-align: center;
        margin-top: 1rem;
        font-size: 1.25rem;
        line-height: 1.5;
    }

    .text-color-white.text-size-small {
        font-weight: 400;
    }

    .icon-tiny {
        height: 0.5rem;
    }

    .icon-tiny.margin-left {
        margin-left: 0.5rem;
    }

    .icon-lottie {
        width: 2rem;
        height: 2rem;
    }

    .footer-link-item {
        color: #0e0e0e;
        text-transform: capitalize;
        font-family: Montserrat, sans-serif;
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.5;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .footer-link-item:hover,
    .footer-link-item.w--current {
        font-weight: 700;
    }

    .footer-link-item.text-style-bold {
        color: #ff9a1f;
    }

    .section-video {
        background-color: #3c86e8;
    }

    .text-color-green {
        color: #43b328;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.5;
    }

    .download-googleplay {
        border-radius: 12px;
    }

    .image-steps {
        height: 20rem;
    }

    .image-steps.margin-bottom {
        height: auto;
        min-height: auto;
        margin-bottom: 1.25rem;
    }

    .p-break {
        width: 33rem;
    }

    .p-break.small {
        width: 16rem;
    }

    .p-break.medium {
        width: 18rem;
    }

    .app-download {
        color: #f9a242;
        text-decoration: none;
    }

    .bg-image-2 {
        width: 100%;
    }

    .bg-video {
        z-index: -1;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0%;
        bottom: auto;
        left: 0%;
        right: auto;
    }

    .character-overlay {
        z-index: 2;
        width: 15rem;
        position: absolute;
        top: -1.5rem;
        bottom: auto;
        left: 3%;
        right: auto;
    }

    .character-overlay.premium {
        width: auto;
        top: -10rem;
        left: -10%;
    }

    .character-overlay.hidden {
        display: none;
    }

    .section-press {
        text-align: center;
        background-color: rgba(27, 186, 255, 0.07);
        padding-top: 2rem;
        padding-bottom: 4rem;
    }

    .floater-right {
        z-index: 1;
        width: 15rem;
        position: absolute;
        top: auto;
        bottom: -5%;
        left: auto;
        right: -15%;
    }

    .link-social {
        width: 3rem;
        height: 3rem;
        border-radius: 10px;
        margin-right: 0.5rem;
        transition: transform 0.2s ease-in-out;
        overflow: hidden;
    }

    .link-social:hover {
        transform: scale(1.05);
    }

    .link-social.margin-right {
        margin-right: 0.5rem;
    }

    .link-social.margin-right:hover {
        transform: scale(0.95);
    }

    .copyright {
        text-align: center;
        font-family: Montserrat, sans-serif;
        font-weight: 700;
    }

    .copyright.margin-top {
        margin-top: 3rem;
    }

    .video-placeholder {
        width: 920px;
        height: 510px;
        background-image: none;
        background-repeat: repeat;
        background-size: auto;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        margin-bottom: 2rem;
        display: flex;
        position: relative;
        overflow: visible;
    }

    .nav_component {
        z-index: 99;
        width: 100%;
        background-color: #fff;
        border-bottom: 1px solid rgba(37, 49, 101, 0.1);
        position: fixed;
    }

    .grind-featured {
        width: 100%;
        max-width: 60rem;
        grid-column-gap: 16px;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        align-items: center;
        justify-items: center;
        margin-left: auto;
        margin-right: auto;
        display: grid;
    }

    .button-login {
        color: #fff;
        text-align: center;
        background-color: #4694f6;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
        padding: 1.25rem 1rem;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.2;
        transition: opacity 0.5s ease-in-out;
        display: flex;
    }

    .button-login:hover {
        color: #fff;
        background-color: rgba(70, 148, 246, 0.75);
    }

    .logo-nav {
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        margin-right: auto;
        display: flex;
    }

    .sub-menu-item {
        color: #0e0e0e;
        text-transform: capitalize;
        margin-bottom: 1rem;
        padding: 0.5rem;
        font-weight: 500;
        text-decoration: none;
        transition: font-size 0.2s ease-in-out;
        display: flex;
    }

    .sub-menu-item:hover {
        font-weight: 700;
    }

    .line-2 {
        width: 100%;
        height: 1px;
        background-color: rgba(70, 148, 246, 0.25);
        margin-left: auto;
        margin-right: auto;
    }

    .grid-learnmore {
        width: 90%;
        grid-column-gap: 3rem;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        grid-template-columns: 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        display: grid;
    }

    .grid-learnmore.margin-top {
        grid-column-gap: 2rem;
        margin-top: 2rem;
        padding-left: 0;
        padding-right: 0;
    }

    .icon-featured {
        max-height: 7rem;
    }

    .section-featured {
        border-bottom: 1px solid rgba(70, 148, 246, 0.25);
        padding-top: 4rem;
        padding-bottom: 6rem;
    }

    .grid-press {
        width: 100%;
        max-width: 72rem;
        grid-column-gap: 16px;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        align-items: center;
        justify-items: center;
        display: grid;
    }

    .grid-testimonial {
        grid-column-gap: 16px;
        grid-row-gap: 16px;
        grid-template-rows: auto auto;
        grid-template-columns: 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        display: grid;
    }

    .grid-testimonial.margin-vertical {
        grid-column-gap: 2rem;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        margin-top: 2rem;
        margin-bottom: 2rem;
        padding-left: 2.9rem;
        padding-right: 2.9rem;
        position: relative;
    }

    .grid-testimonial.margin-vertical.hide {
        display: none;
    }

    .grid-testimonial.margin-vertical-custom {
        grid-column-gap: 2rem;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        margin-top: 8rem;
        margin-bottom: 2rem;
        padding-left: 2.9rem;
        padding-right: 2.9rem;
        position: relative;
    }

    .floater-left {
        z-index: 1;
        width: 15rem;
        position: absolute;
        top: auto;
        bottom: -5%;
        left: -15%;
        right: auto;
    }

    .video-thumb {
        border-radius: 12px;
        position: absolute;
    }

    .icon-play {
        z-index: 2;
        position: static;
        top: 25%;
        bottom: 0%;
        left: 25%;
        right: auto;
    }

    .grid-howitworks {
        width: 90%;
        grid-column-gap: 3rem;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        grid-template-columns: 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        display: grid;
    }

    .grid-howitworks.margin-top {
        margin-top: 2rem;
    }

    .icon-1x1-xlarge {
        width: 8rem;
        height: 8rem;
    }

    .icon-1x1-xlarge.margin-right {
        width: 7rem;
        height: 7rem;
        margin-right: 1.25rem;
    }

    .grid-footerlinks {
        width: 100%;
        max-width: 70rem;
        grid-column-gap: 16px;
        grid-row-gap: 16px;
        grid-template-rows: auto;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-auto-columns: 1fr;
        margin-left: auto;
        margin-right: auto;
        padding-left: 4rem;
        padding-right: 4rem;
        display: grid;
    }

    .grid-footerlinks.margin-vertical {
        margin-top: 3rem;
        margin-bottom: 3rem;
    }

    .download-appstore {
        background-image: none;
        background-position: 0 0;
        background-repeat: repeat;
        background-size: auto;
        border-radius: 12px;
    }

    .download-appstore.margin-right {
        background-image: none;
        margin-right: 1rem;
    }

    .icon-press {
        max-height: 4rem;
    }

    .section-testimonial {
        background-color: #3c86e8;
        background-image: none;
        background-repeat: repeat;
        background-size: auto;
    }

    .mobile-menu {
        display: none;
    }

    .section-benefit {
        height: 75vh;
        /*background-image: url("https://global-uploads.webflow.com/6015e7f6893a7f207ebc1351/627c6a000ccb6b08cd448cbc_homepage-benefit-wave.svg");*/
        background-image: url("/assets/images/characters/homepage-benefit-wave.png");
        background-position: 50% 101%;
        background-repeat: no-repeat;
        background-size: contain;
        padding-top: 2rem;
        position: relative;
    }

    .nav-link-4 {
        color: #0e0e0e;
        justify-content: center;
        align-items: center;
        padding-left: 1rem;
        padding-right: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        transition: color 0.2s ease-in-out;
        display: flex;
    }

    .nav-link-4:hover {
        color: #4694f6;
    }

    .nav-link-4.sub-item {
        font-size: 1rem;
        position: relative;
    }

    .nav-link-4.sub-item.mobile {
        font-size: 1rem;
    }

    .nav-link-4.download {
        color: #3c86e8;
        transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    .nav-link-4.download:hover {
        transform: scale(1.05);
    }

    .nav-link-4.download.hide-tablet {
        padding-left: 0;
        font-size: 1rem;
    }

    .nav-link-4.hide {
        display: none;
    }

    .break {
        width: 10rem;
    }

    .float-test {
        width: 50%;
        position: absolute;
        top: 0%;
        bottom: 0%;
        left: auto;
        right: 0%;
    }

    .float-img {
        width: 10rem;
        position: absolute;
        top: 0%;
        bottom: 0%;
        left: 0%;
        right: 0%;
    }

    .float-img.main-home {
        z-index: 3;
        width: 20rem;
        top: auto;
        bottom: 0%;
        left: auto;
    }

    .float-img.back-home {
        z-index: 2;
        width: 33rem;
        top: 25%;
        bottom: 0%;
        left: auto;
        right: -15%;
    }

    .float-img.back-home-2 {
        z-index: 1;
        width: 37rem;
        top: 30%;
        bottom: 0%;
        left: auto;
        right: -15%;
    }

    .headline {
        font-size: 1.625rem;
    }

    .close-module {
        width: 40px;
        height: 40px;
        cursor: pointer;
        justify-content: center;
        align-items: center;
        margin-left: 10px;
        display: flex;
        position: relative;
    }

    .close-module.float {
        margin-left: 0;
        position: absolute;
        top: 25%;
        bottom: auto;
        left: auto;
        right: 35%;
    }

    .premium-link {
        color: #0e0e0e;
        justify-content: center;
        align-items: center;
        padding-left: 1rem;
        padding-right: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        transition: color 0.2s ease-in-out;
        display: flex;
    }

    .premium-link:hover {
        color: #4694f6;
    }

    .premium-link.show-tablet {
        display: none;
    }

    .text-color-blue.premium-testimony {
        color: #5bb8f9;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.5;
    }

    .text-color-blue.premium-testimony.margin-right {
        margin-right: 1rem;
    }

    .premium-testimony-text {
        text-align: left;
        font-size: 1.3125rem;
        font-weight: 700;
    }

    .stars {
        margin-left: 1rem;
    }

    .text-style-bold {
        color: #636363;
        font-weight: 700;
    }

    .banner-bottom {
        z-index: 89;
        width: 100%;
        text-align: center;
        background-color: #2d7ff9;
        padding: 1rem 1.5rem;
        position: fixed;
        top: auto;
        bottom: 0%;
        left: 0%;
        right: 0%;
    }

    .logo-coolxue {
        max-height: 110px;
    }


    @media screen and (min-width: 1920px) {
        .floater-message.close-button {
            max-width: 1500px;
        }

        .button-secondary-2.with-shadow:hover,
        .button-secondary-2.margin-left:hover,
        .button-secondary-2.dropdown-menu:hover {
            color: #fff;
        }

        .container-large.hero {
            position: relative;
        }

        .button-hero-alt:hover {
            color: #23ce6b;
        }

        .button-hero.margin-right.teacher:hover {
            color: #f9a242;
        }

        .character-overlay.hidden {
            display: none;
        }

        .button-login:hover {
            color: #fff;
        }

        .section-benefit {
            height: 80vh;
            background-position: 0 40%;
            background-size: cover;
        }
    }

    @media screen and (max-width: 991px) {
        h1 {
            font-size: 48px;
        }

        h2 {
            font-size: 33px;
        }

        blockquote {
            padding: 50px 60px;
        }

        .footer {
            padding-top: 60px;
        }

        .mobile-nav {
            background-color: #fff;
            padding-top: 15px;
            box-shadow: 42px 42px 55px rgba(32, 53, 90, 0.09);
        }

        .floater-message.close-button {
            display: none;
        }

        .button-secondary-2.dropdown-menu {
            width: 100%;
            text-align: left;
        }

        .container-large.menu {
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .container-large.footer-cta {
            flex-direction: column;
        }

        .container-large.sub-menu-link {
            width: 100%;
            box-shadow: none;
            align-items: center;
            display: flex;
            position: relative;
        }

        .container-large.show-tablet.padding-custom1 {
            color: #3c86e8;
            padding: 2.5rem;
            display: flex;
        }

        .container-large.nav {
            height: auto;
            flex-direction: column;
            padding-top: 1.25rem;
        }

        .container-large.nav-wrap {
            flex-direction: column;
            display: none;
        }

        .container-large.mobile-nav.show-tablet {
            box-shadow: none;
            background-color: rgba(0, 0, 0, 0);
            flex-direction: row;
            justify-content: space-between;
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            display: flex;
        }

        .container-large.hero-content-wrap.show-desktop {
            display: none;
        }

        .container-large.hero {
            padding-top: 3rem;
            padding-left: 0;
        }

        .container-large.logo {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .container-large.column {
            width: 100%;
        }

        .container-large.column.margin-bottom {
            margin-bottom: 2rem;
        }

        .container-large.benefit-main {
            grid-template-columns: 1.25fr;
        }

        .hide-tablet {
            display: none;
        }

        .margin-left {
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
        }

        .margin-bottom {
            margin-top: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .margin-right {
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
        }

        .button-hero-alt.mobile {
            color: #fff;
            background-color: #43b328;
        }

        .heading-small.text-color-blue {
            color: #3c86e8;
        }

        .margin-top {
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .icon-small.margin-right.responsive {
            height: 100%;
            margin-right: 1rem;
        }

        .container-medium-2.nav-item {
            flex-direction: column;
        }

        .margin-vertical {
            margin-left: 0;
            margin-right: 0;
        }

        .show-tablet {
            display: block;
        }

        .button-hero.margin-right.mobile {
            color: #fff;
            background-color: #f9a242;
        }

        .page-padding {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }

        .container-small.buttons.hide-tablet {
            display: none;
        }

        .section-hero {
            min-height: 500px;
            background-image: url("/assets/images/new_images/Group%2023.webp"),
            linear-gradient(112.5deg, #2991fe 40%, rgba(37, 49, 101, 0.65) 65%);
            background-position: 100% 101%, 0 0;
            background-repeat: no-repeat, repeat;
            background-size: contain, auto;
            padding-top: 10.8rem;
        }

        .icon-tiny.margin-left {
            display: block;
        }

        .spacer-2 {
            display: none;
        }

        .character-overlay {
            width: 15rem;
            top: -15%;
            left: 70%;
        }

        .character-overlay.premium {
            left: auto;
            right: 0%;
        }

        .video-placeholder {
            width: 650px;
            height: 350px;
        }

        .nav_component {
            z-index: 99;
        }

        .button-login {
            width: 45%;
        }

        .sub-menu-item {
            width: 100%;
            text-align: center;
        }

        .grid-learnmore.margin-top {
            grid-column-gap: 3rem;
            grid-row-gap: 3rem;
            grid-template-columns: 1fr;
        }

        .grid-testimonial.margin-vertical,
        .grid-testimonial.margin-vertical-custom {
            grid-column-gap: 2rem;
            grid-row-gap: 2rem;
            grid-template-columns: 1fr;
        }

        .grid-howitworks.margin-top {
            grid-column-gap: 3rem;
            grid-row-gap: 3rem;
            grid-template-columns: 1fr;
        }

        .mobile-menu {
            display: flex;
        }

        .section-benefit {
            height: 1200px;
            background-position: 0 100%;
            background-size: 120%;
        }

        .section-benefit.main {
            height: 75vh;
        }

        .nav-link-4 {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .nav-link-4.sub-item {
            flex-direction: column;
        }

        .nav-link-4.sub-item.mobile {
            flex-direction: row;
        }

        .nav-link-4.download.responsive {
            width: 45%;
            color: #fff;
            background-color: #4694f6;
            border-radius: 12px;
            justify-content: flex-start;
            align-items: center;
            padding: 0;
        }

        .float-test {
            display: none;
        }

        .close-module.float {
            position: absolute;
            top: 25%;
            bottom: auto;
            left: auto;
            right: 15%;
        }

        .premium-link {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .premium-link.show-tablet {
            margin-left: auto;
            margin-right: 0;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            display: none;
        }
    }

    @media screen and (max-width: 767px) {
        h1 {
            font-size: 40px;
        }

        h2 {
            font-size: 28px;
        }

        h3 {
            font-size: 22px;
        }

        blockquote {
            border-radius: 20px;
            padding-left: 40px;
            padding-right: 40px;
            font-size: 22px;
        }

        .footer {
            padding-bottom: 20px;
        }

        .container-large.show-tablet.padding-custom1 {
            padding: 1.25rem;
        }

        .container-large.mobile-nav.show-tablet {
            grid-column-gap: 16px;
            grid-row-gap: 16px;
            flex-direction: column;
            grid-template-rows: auto;
            grid-template-columns: 1fr 1fr;
            grid-auto-columns: 1fr;
            display: grid;
        }

        .container-large.app-download {
            flex-direction: column;
            align-items: center;
        }

        .container-large.hero {
            padding-top: 0;
        }

        .container-large.button-wrap.mobile {
            flex-direction: column;
        }

        .container-large.appstore {
            flex-direction: column;
            align-items: center;
        }

        .margin-left {
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
        }

        .margin-bottom {
            margin-top: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .margin-right {
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
        }

        .button-hero-alt.mobile {
            color: #fff;
            background-color: #43b328;
        }

        .heading-small {
            font-size: 1rem;
        }

        .heading-small.text-color-blue {
            font-size: 1.25rem;
        }

        .heading-medium {
            font-size: 1.5rem;
        }

        .margin-top {
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .heading-large {
            font-size: 2rem;
        }

        .margin-vertical {
            margin-left: 0;
            margin-right: 0;
        }

        .button-hero.margin-right.mobile {
            color: #fff;
            background-color: #f9a242;
            margin-bottom: 1.25rem;
            margin-right: 0;
        }

        .page-padding {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .section-hero {
            min-height: 500px;
            padding-top: 15rem;
        }

        .text-color-white.break {
            width: auto;
            font-size: 3rem;
        }

        .character-overlay {
            width: 10rem;
            top: -9.5%;
        }

        .character-overlay.premium {
            width: 8rem;
            top: -5rem;
        }

        .video-placeholder {
            width: 450px;
            height: 350px;
        }

        .grind-featured {
            max-width: 30rem;
        }

        .button-login {
            width: auto;
        }

        .grid-press {
            max-width: 30rem;
        }

        .grid-testimonial.margin-vertical-custom {
            margin-top: 4rem;
        }

        .icon-play {
            width: 10rem;
        }

        .grid-footerlinks.margin-vertical {
            grid-template-columns: 1fr;
        }

        .download-appstore.margin-right {
            margin-bottom: 1rem;
        }

        .download-appstore.margin-right.module {
            margin-right: 0;
        }

        .nav-link-4.download.responsive {
            width: auto;
            margin-top: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
    }

    @media screen and (max-width: 479px) {
        body {
            font-size: 16px;
        }

        h1 {
            font-size: 34px;
        }

        h2 {
            font-size: 25px;
        }

        h3 {
            font-size: 18px;
        }

        blockquote {
            border-radius: 15px;
            padding: 40px 20px;
            font-size: 19px;
        }

        .footer {
            padding-top: 40px;
        }

        .floater-message.close-button {
            width: 90%;
            max-width: none;
            flex-direction: row;
            align-items: flex-start;
        }

        .image-26.close-button.cookie {
            order: 1;
            align-self: center;
            display: none;
        }

        .block-quote-4.close-button.wording {
            order: -1;
            margin-left: 0;
            padding-left: 0;
        }

        .close-button {
            order: -1;
            justify-content: center;
            margin-left: 0;
        }

        .container-large {
            flex-direction: column;
            align-items: flex-start;
        }

        .container-large.benefit-card {
            align-items: flex-start;
        }

        .container-large.sub-menu-link {
            display: none;
        }

        .container-large.footer-links {
            align-items: center;
        }

        .container-large.show-tablet.padding-custom1 {
            padding: 1.25rem;
        }

        .container-large.nav {
            align-items: stretch;
        }

        .container-large.testimony-card {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .container-large.testimony-card.premium:hover {
            transform: none;
        }

        .container-large.nav-wrap {
            display: flex;
        }

        .container-large.image-cover.margin-bottom {
            width: auto;
        }

        .container-large.mobile-nav.show-tablet {
            flex-direction: column;
        }

        .container-large.app-download {
            flex-direction: column;
            align-items: center;
        }

        .container-large.learn-more {
            width: auto;
        }

        .container-large.hero {
            padding-top: 0;
        }

        .container-large.button-wrap {
            justify-content: space-between;
            padding-left: 0;
            padding-right: 0;
        }

        .container-large.button-wrap.mobile {
            flex-direction: column;
        }

        .container-large.video {
            text-align: center;
        }

        .container-large.column {
            align-items: stretch;
        }

        .container-large.steps {
            width: auto;
        }

        .container-large.download {
            z-index: 299;
        }

        .container-large.premium-testimony-quote {
            flex-direction: row;
            justify-content: flex-start;
        }

        .container-large.premium-testimony-rating {
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .text-size-medium {
            font-size: 1rem;
        }

        .margin-left {
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
        }

        .margin-bottom {
            margin-top: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .margin-right {
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
        }

        .button-hero-alt.mobile {
            color: #fff;
            background-color: #43b328;
        }

        .heading-small.text-color-blue {
            color: #3c86e8;
            font-size: 1.25rem;
            font-weight: 500;
        }

        .margin-top {
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .heading-large.text-color-white {
            text-align: center;
            font-size: 2.25rem;
        }

        .heading-large.text-color-white.margin-bottom {
            margin-bottom: 0;
        }

        .container-medium-2.nav-item {
            display: flex;
        }

        .hide-mobile-portrait {
            display: none;
        }

        .margin-vertical {
            margin-left: 0;
            margin-right: 0;
        }

        .icon-1x1-small.margin-right.no-bg {
            width: 1rem;
            height: 1rem;
        }

        .text-style-link.text-color-white {
            font-size: 1rem;
            line-height: 1.2;
        }

        .button-hero.margin-right.mobile {
            color: #fff;
            background-color: #f9a242;
            margin-bottom: 1.25rem;
            margin-right: 0;
        }

        .text-size-small.text-color-blue {
            color: #4694f6;
        }

        .section-hero {
            min-height: auto;
            padding-top: 12rem;
        }

        .text-color-white.break {
            width: auto;
            font-size: 2.25rem;
        }

        .text-color-white.break.main-header {
            font-size: 2.25rem;
        }

        .text-color-white.margin-top {
            font-size: 1rem;
        }

        .text-color-white.text-size-small {
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .image-steps.margin-bottom {
            height: auto;
        }

        .p-break,
        .p-break.medium {
            width: auto;
            font-size: 1rem;
        }

        .character-overlay {
            width: 9rem;
            top: -6.5%;
            left: 55%;
        }

        .character-overlay.premium.left {
            width: 6rem;
            top: -3.5rem;
        }

        .character-overlay.premium.middle {
            width: 4rem;
            top: -3.5rem;
        }

        .character-overlay.premium.right {
            width: 6rem;
            top: -3.5rem;
        }

        .floater-right {
            width: 5rem;
            bottom: 20%;
        }

        .video-placeholder {
            width: 250px;
            height: 150px;
        }

        .grind-featured {
            grid-row-gap: 2rem;
            grid-template-columns: 1fr;
        }

        .button-login {
            width: auto;
            font-size: 1rem;
        }

        .logo-nav.w--current {
            width: 50%;
        }

        .sub-menu-item {
            text-align: center;
            justify-content: center;
        }

        .grid-learnmore.margin-top {
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .grid-press {
            grid-row-gap: 2rem;
            grid-template-columns: 1fr;
        }

        .grid-testimonial.margin-vertical {
            padding-left: 0;
            padding-right: 0;
        }

        .grid-testimonial.margin-vertical-custom {
            margin-top: 4rem;
            padding-left: 0;
            padding-right: 0;
        }

        .floater-left {
            width: 5rem;
            bottom: 20%;
        }

        .icon-play {
            width: 7rem;
        }

        .grid-howitworks.margin-top {
            width: auto;
        }

        .icon-1x1-xlarge.margin-right {
            width: 4rem;
            height: 4rem;
            margin-top: 0.5rem;
        }

        .grid-footerlinks.margin-vertical {
            padding-left: 0;
            padding-right: 0;
        }

        .download-appstore.margin-right {
            margin-bottom: 1rem;
            margin-right: 0;
        }

        .section-benefit {
            height: 1150px;
            background-image: url("/assets/images/new_images/benefit-wave.svg"),
            linear-gradient(#fff 99%, #3c86e8);
            background-position: 0 100%, 0 0;
            background-repeat: no-repeat, repeat;
            background-size: 120%, auto;
        }

        .section-benefit.main {
            height: auto;
            padding-bottom: 10rem;
        }

        .nav-link-4.download.responsive {
            width: auto;
            text-align: center;
            letter-spacing: 0;
            justify-content: center;
            margin-top: 0;
            padding-left: 0;
            padding-right: 0;
            font-size: 0.875rem;
        }

        .close-module {
            order: -1;
            justify-content: center;
            margin-left: 0;
        }

        .premium-link.show-tablet {
            padding: 0 1rem 0 0;
            font-size: 0.825rem;
        }

        .banner-bottom {
            padding: 1rem;
            font-size: 0.75rem;
        }
    }

    #grid_1_1_1 {
        grid-area: span 1 / span 1 / span 1 / span 1;
    }

    @media screen and (max-width: 479px) {
        #grid_1_1_1_1_mobile {
            align-self: stretch;
        }
    }

    #logo_character, #logo_land, #logo_numbers1, #logo_numbers2, #logo_operator {
	    transition: transform .5s ease-out;
    }

</style>

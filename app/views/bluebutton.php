<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" ng-app> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" ng-app> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" ng-app> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" ng-app> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Blue Button Health Record </title>
        <meta name="description" content="Patient health records in the Blue Button format.">
        <meta name="viewport" content="width=device-width">
        <meta name="author" content="M. Jackson Wilkinson / jackson@jounce.net / @mjacksonw">
        <!-- Injected styles -->
        <style media="screen, projection">
        /* stylesheets/normalize.css */ /*!normalize.css v1.0.1 | MIT License | git.io/normalize */ article,aside,details,figcaption,figure,footer,header,hgroup,nav,section,summary{display:block}audio,canvas,video{display:inline-block;*display:inline;*zoom:1}audio:not([controls]){display:none;height:0}[hidden]{display:none}html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}html,button,input,select,textarea{font-family:sans-serif}body{margin:0}a:focus{outline:thin dotted}a:active,a:hover{outline:0}h1{font-size:2em;margin:.67em 0}h2{font-size:1.5em;margin:.83em 0}h3{font-size:1.17em;margin:1em 0}h4{font-size:1em;margin:1.33em 0}h5{font-size:.83em;margin:1.67em 0}h6{font-size:.75em;margin:2.33em 0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:bold}blockquote{margin:1em 40px}dfn{font-style:italic}mark{background:#ff0;color:#000}p,pre{margin:1em 0}code,kbd,pre,samp{font-family:monospace,serif;_font-family:'courier new',monospace;font-size:1em}pre{white-space:pre;white-space:pre-wrap;word-wrap:break-word}q{quotes:none}q:before,q:after{content:'';content:none}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-0.5em}sub{bottom:-0.25em}dl,menu,ol,ul{margin:1em 0}dd{margin:0 0 0 40px}menu,ol,ul{padding:0 0 0 40px}nav ul,nav ol{list-style:none;list-style-image:none}img{border:0;-ms-interpolation-mode:bicubic}svg:not(:root){overflow:hidden}figure{margin:0}form{margin:0}fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:.35em .625em .75em}legend{border:0;padding:0;white-space:normal;*margin-left:-7px}button,input,select,textarea{font-size:100%;margin:0;vertical-align:baseline;*vertical-align:middle}button,input{line-height:normal}button,html input[type="button"],input[type="reset"],input[type="submit"]{-webkit-appearance:button;cursor:pointer;*overflow:visible}button[disabled],input[disabled]{cursor:default}input[type="checkbox"],input[type="radio"]{box-sizing:border-box;padding:0;*height:13px;*width:13px}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}textarea{overflow:auto;vertical-align:top}table{border-collapse:collapse;border-spacing:0}
        /* stylesheets/screen.css */ body{font-family:Helvetica Neue,Arial,Helvetica,sans-serif;color:#333}section.bb-template{width:800px;margin:0 auto;display:none}.panel,div#demographics,div#allergies,div#medications,div#immunizations,div#history,div#labs{padding:50px 0;border-bottom:1px solid #ddd}.panel h1,div#demographics h1,div#allergies h1,div#medications h1,div#immunizations h1,div#history h1,div#labs h1{font-size:30px;margin-bottom:30px}a{color:inherit;text-decoration:none}a:hover{text-decoration:underline}ul.pills{overflow:hidden;*zoom:1;margin:0;padding:10px 0 0 0}ul.pills li{float:left;display:inline-block;padding:2px 7px;margin-right:5px;background:#ddd;-webkit-border-radius:20px;-moz-border-radius:20px;-ms-border-radius:20px;-o-border-radius:20px;border-radius:20px;font-size:12px;border:1px solid #ccc}.listless,nav#primaryNav ul,div#allergies ul,div#medications ul,div#immunizations>ul,div#history>ul,div#labs>ul,div#labs ul.results{overflow:hidden;*zoom:1;list-style-type:none;margin:0;padding:0}.module,div#allergies li,div#medications ul>li,div#immunizations>ul>li{float:left;margin:0 20px 20px 0;padding:20px;width:380px;background:#f8f8f8;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.module h2,div#allergies li h2,div#medications ul>li h2,div#immunizations>ul>li h2,.module p,div#allergies li p,div#medications ul>li p,div#immunizations>ul>li p{margin-top:0;margin-bottom:2px;font-size:18px}.module p,div#allergies li p,div#medications ul>li p,div#immunizations>ul>li p,.module header small,div#allergies li header small,div#medications ul>li header small,div#immunizations>ul>li header small{font-weight:300;font-size:18px}.container{overflow:hidden;*zoom:1;width:800px;margin:0 auto}nav#primaryNav{position:fixed;top:0;left:0;width:100%;height:50px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background:#eee;background-image:-webkit-gradient(linear,50% 0,50% 100%,color-stop(0%,#eee),color-stop(100%,#ddd));background-image:-webkit-linear-gradient(top,#eee,#ddd);background-image:-moz-linear-gradient(top,#eee,#ddd);background-image:-o-linear-gradient(top,#eee,#ddd);background-image:linear-gradient(top,#eee,#ddd);-webkit-box-shadow:0 0 15px rgba(0,0,0,0.1);-moz-box-shadow:0 0 15px rgba(0,0,0,0.1);box-shadow:0 0 15px rgba(0,0,0,0.1);z-index:900}nav#primaryNav h1{margin:12px 0;padding:0;width:350px;float:left;font-size:20px;font-weight:normal;color:#bbb}nav#primaryNav ul{float:right}nav#primaryNav ul li{padding:18px 0;float:left;margin-right:15px;font-size:11px;text-transform:uppercase;color:#666}nav#primaryNav ul li:hover{border-bottom:2px solid #aaa}nav#primaryNav ul li a:hover{text-decoration:none}div#demographics{font-size:26px;font-weight:300}div#demographics h1{font-size:50px}div#demographics strong.severe{color:#f8f8f8;background:#c33}div#demographics dl{overflow:hidden;*zoom:1;list-style-type:none;font-size:18px}div#demographics dl li{width:25%;float:left}div#demographics dt{text-transform:lowercase;color:#666}div#demographics dd{font-weight:bold;margin-left:0}div#allergies li.allergy-severe{background:#c33;color:#f8f8f8}div#allergies li.allergy-moderate{background:#e70;color:#f8f8f8}div#medications ul>li{border:1px solid #ddd}div#medications ul>li.odd{clear:left}div#medications ul>li dl{overflow:hidden;*zoom:1;font-size:13px}div#medications ul>li dl li{width:50%;float:left}div#medications ul>li dl dt{font-weight:300;color:#666}div#medications ul>li dl dd{margin:0;font-weight:bold}div#immunizations>ul>li{border:1px solid #ddd}div#history>ul{padding-left:40px;margin-left:20px;border-left:1px solid #ddd;z-index:1}div#history>ul>li:before{content:".";display:block;position:absolute;background:#666;height:35px;width:35px;text-indent:100%;overflow:hidden;margin-left:-60px;z-index:999}div#history>ul>li h2{font-size:18px;font-weight:bold;margin-top:0;padding:6px 0;margin-bottom:20px}div#history>ul>li dl>li{margin-bottom:30px}div#history>ul>li dt{color:#666;font-size:20px;font-weight:300;text-transform:lowercase}div#history>ul>li dd{color:#666;font-size:20px;margin:0;padding:0;font-weight:300}div#history>ul>li dd.head{font-size:24px;color:#333;font-weight:bold}div#history>ul>li dd.head:before{content:".";display:block;position:absolute;background:#666;height:15px;width:15px;text-indent:100%;overflow:hidden;margin-left:-48px;margin-top:10px;z-index:999}div#labs h2 .date{float:right;font-weight:300;color:#666}div#labs ul.results{display:table;width:100%;border:1px solid #ddd;border-right:none;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}div#labs ul.results li{display:table-row}div#labs ul.results li.header span{background:#ddd;font-weight:bold}div#labs ul.results span{display:table-cell;padding:20px;border-right:1px solid #ddd;color:#666}div#labs ul.results span.lab-component{font-weight:bold}div#loader{display:none;width:304px;margin:100px auto;text-align:center;color:#ccc}div#loader #warningGradientOuterBarG{height:38px;width:304px;border:2px solid #eee;overflow:hidden;background-color:#f8f8f8;background-image:-webkit-gradient(linear,50% 0,50% 100%,color-stop(0%,#f8f8f8),color-stop(100%,#eee));background-image:-webkit-linear-gradient(top,#f8f8f8,#eee);background-image:-moz-linear-gradient(top,#f8f8f8,#eee);background-image:-o-linear-gradient(top,#f8f8f8,#eee);background-image:linear-gradient(top,#f8f8f8,#eee)}div#loader .warningGradientBarLineG{background-color:#f8f8f8;float:left;width:27px;height:228px;margin-right:46px;margin-top:-53px;-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg)}div#loader .warningGradientAnimationG{width:448px;-moz-animation-name:warningGradientAnimationG;-moz-animation-duration:1.3s;-moz-animation-iteration-count:infinite;-moz-animation-timing-function:linear;-webkit-animation-name:warningGradientAnimationG;-webkit-animation-duration:1.3s;-webkit-animation-iteration-count:infinite;-webkit-animation-timing-function:linear;-ms-animation-name:warningGradientAnimationG;-ms-animation-duration:1.3s;-ms-animation-iteration-count:infinite;-ms-animation-timing-function:linear;-o-animation-name:warningGradientAnimationG;-o-animation-duration:1.3s;-o-animation-iteration-count:infinite;-o-animation-timing-function:linear;animation-name:warningGradientAnimationG;animation-duration:1.3s;animation-iteration-count:infinite;animation-timing-function:linear}@-moz-keyframes warningGradientAnimationG{0%{margin-left:-72px}100%{margin-left:0}}@-webkit-keyframes warningGradientAnimationG{0%{margin-left:-72px}100%{margin-left:0}}@-ms-keyframes warningGradientAnimationG{0%{margin-left:-72px}100%{margin-left:0}}@-o-keyframes warningGradientAnimationG{0%{margin-left:-72px}100%{margin-left:0}}@keyframes warningGradientAnimationG{0%{margin-left:-72px}100%{margin-left:0}}

        </style>
        <style media="print">
        
        </style>
        <!-- Injected scripts -->
        <script>
		<?php echo $js; ?>
        </script>
    </head>
    <body>
        <section class="bb-template">
            <nav id="primaryNav">
                <div class="container">
                    <h1>Blue Button Health Record</h1>
                    <ul>
                        <li><a href="#demographics">Profile</a></li>
                        <li><a href="#allergies">Allergies</a></li>
                        <li><a href="#medications">Medications</a></li>
                        <li><a href="#immunizations">Immunizations</a></li>
                        <li><a href="#history">History</a></li>
                        <li><a href="#labs">Labs</a></li>
                    </ul>
                </div>
            </nav>
            <div id="demographics" class="panel">
                <h1>{{demographics.name|full_name}}</h1>
                <p class="narrative">
                    <span class="general">
                        <strong>{{demographics.name|display_name}}</strong> is a {% if demographics.dob %}<strong>{{demographics.dob|age}}</strong> year old{% endif %}
                        <strong>{% if demographics.race %}{{demographics.race}} {% endif %}{% if demographics.marital_status %}{{demographics.marital_status|lower}} {% endif %}{{demographics.gender|lower}}</strong>
                        {% if demographics.religion or demographics.language %}who {% if demographics.religion %}is <strong>{{demographics.religion}}</strong>{% if demographics.language %} and {% endif %}{% endif %}{% if demographics.language %}speaks <strong>{{demographics.language|isolanguage|title}}</strong>{% endif %}{% endif %}.
                    </span>
                    <span class="allergies">
                        {{demographics.gender|gender_pronoun|title}} has <strong class="{{allergies|max_severity}}">{{allergies|max_severity}} allergies</strong>.
                    </span>
                    <span class="yearReview">
                        In the past year, {{demographics.gender|gender_pronoun}}
                        <span id="yearReviewEncounters">
                            {% if encounters|since_days(365)|strict_length == 0 %}
                                did not have medical encounters
                            {% else %}
                                had <strong>medical encounters</strong>
                            {% endif %}
                        </span> and has <span id="yearReviewMedications">
                            {% if medications|since_days(365)|strict_length == 0 %}
                                not had any medications prescribed.
                            {% else %}
                                been <strong>prescribed medications</strong>.
                            {% endif %}
                        </span>
                    </span>
                </p>
                <dl id="demographicsExtras">
                    <li>
                        <dt>Birthday</dt>
                        <dd>{{demographics.dob|date("F j, Y")}}</dd>
                    </li>
                    <li>
                        <dt>Address</dt>
                        {% if demographics.address.street|length == 2 %}
                            {% for line in demographics.address.street %}
                            <dd>{{line}}</dd>
                            {% endfor %}
                        {% else %}
                        <dd>{{demographics.address.street}}</dd>
                        {% endif %}
                        <dd>{{demographics.address.city}}, {{demographics.address.state}} {{demographics.address.zip}}</dd>
                    </li>
                    <li>
                        <dt>Telephone</dt>
                        {% for number in demographics.phone %}
                            {% if number %}<dd class="phone-{{loop.key}}">{{loop.key|slice(0,1)}}: <a href="{{number}}">{{number|format_phone}}</a></dd>{% endif %}
                        {% else %}
                            <dd>No known number</dd>
                        {% endfor %}
                    </li>
                    {% if demographics.guardian and demographics.guardian.name.family %}<li>
                        <dt>{{demographics.guardian.relationship|fallback("Guardian")}}</dt>
                        <dd>{{demographics.guardian.name|full_name}}</dd>
                        {% for number in demographics.guardian.phone %}
                            {% if number %}<dd class="phone-{{loop.key}}">{{loop.key|slice(0,1)}}: <a href="{{number}}">{{number|format_phone}}</a></dd>{% endif %}
                        {% else %}
                            <dd>No known number</dd>
                        {% endfor %}
                    </li>{% endif %}
                </dl>
            </div>
            <div id="allergies" class="panel">
                <h1>Allergies</h1>
                {% for allergy in allergies %}
                    {% if loop.first %}<ul>{% endif %}
                    <li class="allergy-{{allergy|max_severity}}">
                        <h2>{{allergy.allergen.name}}</h2>
                        {% if allergy.severity %}<p>{{allergy.severity}}</p>{% endif %}
                        {% if allergy.reaction.name %}<p>Causes {{allergy.reaction.name|lower}}</p>{% endif %}
                    </li>
                    {% if loop.last %}</ul>{% endif %}
                {% else %}
                    <p>No known allergies</p>
                {% endfor %}
            </div>
            <div id="medications" class="panel">
                <h1>Medication History</h1>
                {% for med in medications %}
                    {% if loop.first %}<ul>{% endif %}
                    <li class="{{loop.cycle('odd', 'even')}}">
                        <header>
                            <h2>{{med.product.name}}</h2>
                            {% if med.administration.name %}<small>{{med.administration.name|title}}</small>{% endif %}
                            {% if med.reason.name %}<small>for {{med.reason.name}}</small>{% endif %}
                        </header>

                        <dl class="footer">
                            {% if med.prescriber.organization or med.prescriber.person %}<li>
                                <dt>Prescriber</dt>
                                {% if med.prescriber.organization %}<dd>{{med.prescriber.organization}}</dd>{% endif %}
                                {% if med.prescriber.person %}<dd>{{med.prescriber.person}}</dd>{% endif %}
                            </li>{% endif %}
                            {% if med.date_range.start or med.date_range.end %}<li>
                                <dt>Date</dt>
                                <dd>
                                    {% if med.date_range.start %}{{med.date_range.start|date('M j, Y')}}{% endif %}
                                    {% if med.date_range.end %}&ndash; {{med.date_range.end|date('M j, Y')}}{% endif %}
                                </dd>
                            </li>{% endif %}
                        </dl>
                    </li>
                    {% if loop.last %}</ul>{% endif %}
                {% else %}
                    <p>No known medications</p>
                {% endfor %}
            </div>
            <div id="immunizations" class="panel">
                <h1>Immunizations</h1>
                {% for group in immunizations|group('product.name') %}
                    {% if loop.first %}<ul>{% endif %}
                    <li>
                        <h2>{{group.grouper}}</h2>
                        {% for item in group.list %}
                            {% if loop.first %}<ul class="pills">{% endif %}
                            <li>{{item.date|date('M j, Y')}}</li>
                            {% if loop.last %}</ul>{% endif %}
                        {% endfor %}
                    </li>
                    {% if loop.last %}</ul>{% endif %}
                {% else %}
                    <p>No known immunizations</p>
                {% endfor %}
            </div>
            <div id="history" class="panel">
                <h1>Medical History</h1>
                {% for encounter in encounters %}
                    {% if loop.first %}<ul>{% endif %}
                    <li>
                        <h2>{{encounter.date|date('M j, Y')}}</h2>
                        <dl>
                            <li>
                                <dt>Encounter</dt>
                                <dd class="head">{{encounter.name|fallback("Unknown Visit")|title}}</dd>
                                {% if encounter.finding.name %}<dd>Finding: {{encounter.finding.name}}</dd>{% endif %}
                            </li>
                            {% for problem in encounter|related_by_date('problems') %}
                                <li>
                                    <dt>Problem</dt>
                                    <dd class="head">{{problem.name}}</dd>
                                </li>
                            {% endfor %}
                            {% for procedure in encounter|related_by_date('procedures') %}
                                <li>
                                    <dt>Procedure</dt>
                                    <dd class="head">{{procedure.name}}</dd>
                                </li>
                            {% endfor %}
                            {% for medication in encounter|related_by_date('medications') %}
                                <li>
                                    <dt>Medication</dt>
                                    <dd class="head">{{medication.product.name}}</dd>
                                </li>
                            {% endfor %}
                            {% for immunization in encounter|related_by_date('immunizations') %}
                                <li>
                                    <dt>Immunization</dt>
                                    <dd class='head'>{{immunization.product.name}}</dd>
                                </li>
                            {% endfor %}
                        </dl>
                    </li>
                    {% if loop.last %}</ul>{% endif %}
                {% else %}
                    <p>No known past encounters</p>
                {% endfor %}
            </div>
            <div id="labs" class="panel">
                <h1>Lab Results</h1>
                {% for panel in labs %}
                    {% if loop.first %}<ul>{% endif %}
                    <li>
                        <h2>
                            <span class="date">{{panel.results[0].date|date('M j, Y')}}</span>
                            {{panel.name|fallback("Laboratory Panel")}}
                        </h2>
                        <ul class="results">
                            <li class="header">
                                <span class="lab-component">Component</span>
                                <span class="lab-value">Value</span>
                                <span class="lab-low">Low</span>
                                <span class="lab-high">High</span>
                            </li>
                            {% for result in panel.results %}
                                <li>
                                    <span class="lab-component">{{result.name}}</span>
                                    <span class="lab-value">{{result.value|fallback("Unknown")}}{% if result.unit %} {{result.unit|format_unit|raw}}{% endif %}</span>
                                    <span class="lab-low">{% if result.reference.low %}{{result.reference.low}}{% endif %}</span>
                                    <span class="lab-high">{% if result.reference.high %}{{result.reference.high}}{% endif %}</span>
                                </li>
                            {% endfor %}
                        </ul>
                    </li>
                    {% if loop.last %}</ul>{% endif %}
                {% endfor %}
            </div>
        </section>
        <div id="loader">
            <div id="warningGradientOuterBarG">
                <div id="warningGradientFrontBarG" class="warningGradientAnimationG">
                    <div class="warningGradientBarLineG"></div>
                    <div class="warningGradientBarLineG"></div>
                    <div class="warningGradientBarLineG"></div>
                    <div class="warningGradientBarLineG"></div>
                    <div class="warningGradientBarLineG"></div>
                    <div class="warningGradientBarLineG"></div>
                </div>
            </div>
            <p>Reticulating splines...</p>
        </div>
    </body>
</html>
<!-- Injected patient data -->
<script style="display: none;" id="xmlBBData" type="text/plain">
<?php echo $ccda;?>
</script>

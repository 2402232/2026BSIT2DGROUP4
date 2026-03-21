<?php require_once VIEW_PATH . 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/safety-guides.css'; ?>">
<link rel="stylesheet" href="<?php echo ASSETS_PATH . 'css/guide-detail.css'; ?>">

<?php
$guides = [
    'cpr-instructions' => [
        'title'    => 'CPR Instructions',
        'category' => 'Medical',
        'read'     => '5 min read',
        'icon'     => 'ri-heart-pulse-line',
        'video_query' => 'how+to+perform+CPR+step+by+step+tutorial',
        'intro'    => 'Cardiopulmonary resuscitation (CPR) is a life-saving technique that can double or triple a person\'s chance of survival after cardiac arrest. Learn the proper steps below.',
        'steps'    => [
            ['num'=>1, 'title'=>'Check for Danger', 'desc'=>'Ensure the scene is safe for both you and the victim. Look for hazards such as traffic, fire, or electrical wires before approaching.'],
            ['num'=>2, 'title'=>'Check for Response', 'desc'=>'Tap the person\'s shoulders firmly and shout "Are you okay?" If there is no response, the person is unresponsive.'],
            ['num'=>3, 'title'=>'Call for Help', 'desc'=>'Call 911 or your local emergency number immediately. Ask a bystander to call if available. Send someone to get an AED if nearby.'],
            ['num'=>4, 'title'=>'Open the Airway', 'desc'=>'Place the person on their back. Tilt the head back gently and lift the chin to open the airway.'],
            ['num'=>5, 'title'=>'Check for Breathing', 'desc'=>'Look, listen, and feel for breathing for no more than 10 seconds. If not breathing normally, begin CPR.'],
            ['num'=>6, 'title'=>'Give 30 Chest Compressions', 'desc'=>'Place both hands on the center of the chest. Push down hard and fast — at least 2 inches deep — at a rate of 100–120 compressions per minute.'],
            ['num'=>7, 'title'=>'Give 2 Rescue Breaths', 'desc'=>'Tilt the head back, pinch the nose, and give a breath lasting 1 second. Watch for chest rise. Give a second breath.'],
            ['num'=>8, 'title'=>'Repeat Until Help Arrives', 'desc'=>'Continue cycles of 30 compressions and 2 breaths until professional help arrives, an AED is available, or the person shows signs of life.'],
        ],
        'tips' => [
            'Push hard and fast — do not be afraid of breaking ribs; it is better than death.',
            'Allow full chest recoil between compressions.',
            'Minimize interruptions to compressions.',
            'If you are untrained, do Hands-Only CPR (compressions only).',
        ],
    ],
    'treating-burns' => [
        'title'    => 'Treating Burns',
        'category' => 'Medical',
        'read'     => '3 min read',
        'icon'     => 'ri-fire-line',
        'video_query' => 'how+to+treat+burns+first+aid+tutorial',
        'intro'    => 'Burns are classified into degrees based on depth and severity. Knowing how to respond immediately can reduce damage and prevent infection.',
        'steps'    => [
            ['num'=>1, 'title'=>'Ensure Safety', 'desc'=>'Remove the person from the source of the burn. Turn off the power for electrical burns. Do not touch a person still in contact with electricity.'],
            ['num'=>2, 'title'=>'Cool the Burn', 'desc'=>'Run cool (not cold) running water over the burn for at least 10–20 minutes. Do not use ice, butter, or toothpaste.'],
            ['num'=>3, 'title'=>'Remove Clothing & Jewelry', 'desc'=>'Carefully remove clothes and jewelry around the burn area UNLESS they are stuck to the skin. Do not pull off stuck material.'],
            ['num'=>4, 'title'=>'Cover the Burn', 'desc'=>'Cover loosely with a clean, non-fluffy material (cling wrap or a clean plastic bag). Do not use cotton wool or anything that may stick.'],
            ['num'=>5, 'title'=>'Assess Severity', 'desc'=>'First-degree: redness, pain. Second-degree: blisters. Third-degree: charred or white skin. Seek emergency care for second and third degree burns.'],
            ['num'=>6, 'title'=>'Seek Medical Help', 'desc'=>'Go to the emergency room for burns larger than 3 inches, burns on face/hands/feet/genitals/joints, chemical or electrical burns, or any third-degree burn.'],
        ],
        'tips' => [
            'Never burst blisters — they protect against infection.',
            'Keep the person warm to prevent shock.',
            'Do not apply creams or greasy substances.',
            'Give pain reliever if available and the person is conscious.',
        ],
    ],
    'snake-bite-response' => [
        'title'    => 'Snake Bite Response',
        'category' => 'Medical',
        'read'     => '4 min read',
        'icon'     => 'ri-alert-line',
        'video_query' => 'snake+bite+first+aid+response+tutorial',
        'intro'    => 'Snake bites can be life-threatening. Acting quickly and correctly — and avoiding dangerous myths — is critical for the victim\'s survival.',
        'steps'    => [
            ['num'=>1, 'title'=>'Stay Calm & Move Away', 'desc'=>'Keep the victim and yourself calm. Move away from the snake. Do not attempt to capture or kill it — a photo from a safe distance is enough for identification.'],
            ['num'=>2, 'title'=>'Call Emergency Services', 'desc'=>'Call 911 or the nearest hospital immediately. Time is critical, especially with venomous snakes.'],
            ['num'=>3, 'title'=>'Immobilize the Bitten Area', 'desc'=>'Keep the bitten limb still and at or below heart level. Movement speeds venom absorption.'],
            ['num'=>4, 'title'=>'Remove Constricting Items', 'desc'=>'Remove rings, watches, and tight clothing near the bite before swelling starts.'],
            ['num'=>5, 'title'=>'Mark the Swelling', 'desc'=>'Use a pen to mark the edge of swelling and note the time. This helps medical staff assess venom spread.'],
            ['num'=>6, 'title'=>'Transport to Hospital', 'desc'=>'Get to a hospital as fast as possible. Anti-venom is the definitive treatment and must be administered by medical professionals.'],
        ],
        'tips' => [
            'DO NOT suck out venom — this is a myth and causes more harm.',
            'DO NOT apply ice or a tourniquet.',
            'DO NOT cut the wound.',
            'Keep the victim still and reassured.',
        ],
    ],
    'earthquake-safety' => [
        'title'    => 'Earthquake Safety',
        'category' => 'Disaster',
        'read'     => '6 min read',
        'icon'     => 'ri-earth-line',
        'video_query' => 'earthquake+safety+what+to+do+tutorial',
        'intro'    => 'Earthquakes strike without warning. Knowing what to do before, during, and after an earthquake can save your life and the lives of people around you.',
        'steps'    => [
            ['num'=>1, 'title'=>'Drop, Cover, and Hold On', 'desc'=>'Drop to your hands and knees. Take cover under a sturdy desk or table, or against an interior wall. Hold on until the shaking stops.'],
            ['num'=>2, 'title'=>'Stay Inside', 'desc'=>'Do not run outside during shaking. Most injuries occur when people try to move or run. Stay where you are.'],
            ['num'=>3, 'title'=>'Stay Away from Windows', 'desc'=>'Move away from windows, glass, and exterior walls. Flying glass is a major cause of injury.'],
            ['num'=>4, 'title'=>'If Outdoors, Find Open Space', 'desc'=>'Move away from buildings, trees, streetlights, and power lines. Once in the open, drop and stay until shaking stops.'],
            ['num'=>5, 'title'=>'After Shaking Stops — Check for Injuries', 'desc'=>'Check yourself and those around you. Provide first aid if needed. Do not move seriously injured people unless in immediate danger.'],
            ['num'=>6, 'title'=>'Watch for Aftershocks', 'desc'=>'Aftershocks may follow. Be prepared to Drop, Cover, and Hold On again. Evacuate damaged buildings carefully.'],
            ['num'=>7, 'title'=>'Avoid Hazards', 'desc'=>'Check for gas leaks (smell), electrical damage, and structural damage before re-entering a building. If you smell gas, leave immediately.'],
        ],
        'tips' => [
            'Prepare a go-bag with water, food, flashlight, and first aid kit.',
            'Know your building\'s evacuation plan and meeting point.',
            'Follow instructions from emergency management officials.',
            'Do not use elevators after an earthquake.',
        ],
    ],
    'choking-relief' => [
        'title'    => 'Choking Relief',
        'category' => 'Medical',
        'read'     => '2 min read',
        'icon'     => 'ri-lungs-line',
        'video_query' => 'choking+relief+Heimlich+maneuver+tutorial',
        'intro'    => 'Choking occurs when a foreign object blocks the throat or windpipe. Immediate action is critical — choking can cause death within minutes.',
        'steps'    => [
            ['num'=>1, 'title'=>'Identify Choking', 'desc'=>'Signs: person cannot speak, cough, or breathe; is clutching throat (universal choking sign); face may turn blue or red.'],
            ['num'=>2, 'title'=>'Ask if They Can Speak', 'desc'=>'If they can cough or speak, encourage them to keep coughing. Do not interfere — a strong cough is the best way to clear an airway.'],
            ['num'=>3, 'title'=>'Give 5 Back Blows', 'desc'=>'Lean the person forward. Give 5 firm blows between shoulder blades with the heel of your hand. Check if the object is dislodged after each blow.'],
            ['num'=>4, 'title'=>'Give 5 Abdominal Thrusts (Heimlich)', 'desc'=>'Stand behind them, wrap arms around waist. Make a fist above the navel. Thrust sharply inward and upward. Repeat up to 5 times.'],
            ['num'=>5, 'title'=>'Alternate Until Clear or Help Arrives', 'desc'=>'Alternate between 5 back blows and 5 abdominal thrusts. If the person becomes unconscious, call 911 and begin CPR.'],
        ],
        'tips' => [
            'For infants under 1 year: use 5 back blows and 5 chest thrusts — NOT abdominal thrusts.',
            'For pregnant women: use chest thrusts instead of abdominal thrusts.',
            'After Heimlich maneuver, the person should be seen by a doctor.',
            'If alone and choking, call 911 then try to perform self-Heimlich against a chair back.',
        ],
    ],
    'flood-evacuation' => [
        'title'    => 'Flood Evacuation',
        'category' => 'Disaster',
        'read'     => '5 min read',
        'icon'     => 'ri-flood-line',
        'video_query' => 'flood+evacuation+safety+tips+tutorial',
        'intro'    => 'Flooding is one of the most common and deadly natural disasters. Knowing when and how to evacuate can mean the difference between life and death.',
        'steps'    => [
            ['num'=>1, 'title'=>'Monitor Warnings', 'desc'=>'Stay informed via radio, TV, or mobile alerts. Know the difference: Flood Watch = flooding possible. Flood Warning = flooding is occurring or imminent.'],
            ['num'=>2, 'title'=>'Prepare Your Go-Bag', 'desc'=>'Pack essentials: water (3-day supply), food, medications, important documents, flashlight, first aid kit, phone charger, and warm clothing.'],
            ['num'=>3, 'title'=>'Evacuate Early', 'desc'=>'Do not wait for mandatory evacuation orders if you feel unsafe. It is always safer to evacuate too early than too late.'],
            ['num'=>4, 'title'=>'Avoid Floodwater', 'desc'=>'Never walk, swim, or drive through floodwater. Just 6 inches of moving water can knock you down; 12 inches can sweep a vehicle away.'],
            ['num'=>5, 'title'=>'Turn Off Utilities', 'desc'=>'Turn off electricity, gas, and water at the main switches before leaving. Do not touch electrical equipment if wet or standing in water.'],
            ['num'=>6, 'title'=>'Follow Evacuation Routes', 'desc'=>'Use designated evacuation routes. Do not take shortcuts — roads may be washed out. Follow instructions from local authorities.'],
            ['num'=>7, 'title'=>'Go to Higher Ground', 'desc'=>'Move to a designated shelter or the highest floor of a solid building if evacuation is not possible. Do not go to the basement.'],
        ],
        'tips' => [
            'Know your community\'s flood risk and evacuation zones.',
            'Keep important documents in a waterproof container.',
            'After flooding, do not return home until authorities say it is safe.',
            'Document damage with photos before cleaning up for insurance purposes.',
        ],
    ],
];

$slug  = $_GET['guide'] ?? '';
$guide = $guides[$slug] ?? null;

if (!$guide): ?>
    <div class="guides-wrapper" style="text-align:center; padding-top:120px;">
        <h2>Guide not found.</h2>
        <a href="index.php?action=safety-guides" class="back-link">← Back to Safety Guides</a>
    </div>
<?php else: ?>

<div class="detail-wrapper">

    <a class="back-link" href="index.php?action=safety-guides">← Back to Safety Guides</a>

    <!-- Hero -->
    <div class="detail-hero">
        <div class="detail-hero-icon">
            <i class="<?php echo $guide['icon']; ?>"></i>
        </div>
        <div>
            <span class="guide-category"><?php echo $guide['category']; ?></span>
            <h1><?php echo $guide['title']; ?></h1>
            <p class="detail-intro"><?php echo $guide['intro']; ?></p>
            <span class="guide-read"><i class="ri-time-line"></i> <?php echo $guide['read']; ?></span>
        </div>
    </div>

    <div class="detail-body">

        <!-- Video Section -->
        <div class="detail-card">
            <h2 class="section-title"><i class="ri-play-circle-line"></i> Video Tutorial</h2>
            <div class="video-container">
                <iframe
                    src="https://www.youtube.com/embed/<?php echo $guide['video_id']; ?>?rel=0"
                    title="<?php echo $guide['title']; ?> Tutorial"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Step-by-Step Instructions -->
        <div class="detail-card">
            <h2 class="section-title"><i class="ri-list-ordered"></i> Step-by-Step Instructions</h2>
            <div class="steps-list">
                <?php foreach ($guide['steps'] as $step): ?>
                <div class="step-item">
                    <div class="step-num"><?php echo $step['num']; ?></div>
                    <div class="step-content">
                        <h3><?php echo $step['title']; ?></h3>
                        <p><?php echo $step['desc']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tips -->
        <div class="detail-card tips-card">
            <h2 class="section-title"><i class="ri-lightbulb-line"></i> Important Tips</h2>
            <ul class="tips-list">
                <?php foreach ($guide['tips'] as $tip): ?>
                <li><i class="ri-checkbox-circle-fill"></i> <?php echo $tip; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Emergency CTA -->
        <div class="detail-card emergency-cta">
            <i class="ri-phone-line cta-icon"></i>
            <div>
                <h3>In a Real Emergency?</h3>
                <p>Do not rely on this guide alone. Call emergency services immediately.</p>
            </div>
            <a href="index.php?action=report-system" class="cta-btn">
                <i class="ri-alarm-warning-line"></i> Report Emergency
            </a>
        </div>

    </div>
</div>

<?php endif; ?>

<?php include 'views/includes/footer.php'; ?>
<script src="<?php echo ASSETS_PATH . 'js/safety-guides.js'; ?>"></script>
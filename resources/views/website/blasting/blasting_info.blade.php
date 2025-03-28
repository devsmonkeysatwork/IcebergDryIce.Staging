@extends('website.layouts.main')

@section('content')
<div class="container">
    <!-- MEDIA BLASTING COMPARISON -->
    <div class='info'>
        <h1 class='info_header'>Media Blasting Comparison</h1>

        There are several different types of media that can be used to blast clean surfaces.  Each one has different advantages
        and disadvantages.  This table shows a handful of criteria for comparison which are explained in more depth below.
        At Iceberg Dry Ice we want you to use the right tool for the right job and when a different media would be more effective
        for your job we are happy to help find you the right solution.<br>
        <br>
        <table style="font-size: smaller" class="table_horz" rules='rows'>
            <tr>
                <th width="200px">Media</th>
                <th width="75px">Mose</th>
                <th width="90px">Clean Up</th>
                <th>Advantages</th>
                <th>Disadvantages</th>
            </tr><tr>
                <td>Dry Ice</td>
                <td>Soft</td>
                <td>0 lbs.</td>
                <td><font color="blue">Cold</font>, Thermal Shock, <font color="green">Environmentally Friendly</font></td>
                <td><font color="red">Oxygen Displacement</font></td>
            </tr><tr>
                <td>Soda</td>
                <td>Soft</td>
                <td>450 lbs.</td>
                <td>Buffers Smoke Smell</td>
                <td></td>
            </tr><tr>
                <td>Walnut Shells, Corn Cobs, Sponge, Plastic Bead</td>
                <td>Medium</td>
                <td>1800 lbs.</td>
                <td></td>
                <td></td>
            </tr><tr>
                <td>Coal Slag, Glass</td>
                <td>Hard</td>
                <td>1800 lbs.</td>
                <td></td>
                <td></td>
            </tr><tr>
                <td>Water</td>
                <td>Med-Hard</td>
                <td>1800 L</td>
                <td>Inexpensive</td>
                <td><font color="red">Mould, Conductive</font></td>
            </tr><tr>
                <td><font color="#666666">Silica Sand</font></td>
                <td><font color="#666666">Hard</font></td>
                <td><font color="#666666">1800 lbs.</font></td>
                <td></td>
                <td><font color="#666666">Carcinogenic</font></td>
            </tr><tr>
                <td>Solvents</td>
                <td>Soft</td>
                <td>special</td>
                <td></td>
                <td><font color="red">Environmentally UNfriendly</font></td>
            </tr>
        </table>
        <br><br>

        <div id="t1">Media</div>
        <p>
            This is what type of media you are propelling at your cleaning surface.  Silica sand can't be used anymore
            because it has been deemed carcinogenic by WorkSafe BC.
        </p>
        <div id="t1">Mose</div>
        <p>
            The Mose scale is used to rate hardness with talc being a 1, and diamonds being a 10.  The harder the media the more aggressive it is
            in cleaning and the more damage it does to your surface.  Hard media like glass and coal slag can pit metals and will destroy
            wood but because of this they can clean rust or do cement finishing.  Softer media like dry ice and soda won't effect cement, but they
            can be used on wood finising, or circuit boards safely.
        </p>
        <p>
            <b>Fryability</b> is a measure of how easily the media breaks apart.  Dry ice and soda are highly fryable which means that instead of
            causing damage to your surfaces, they disintegrate on contact.  This is why they are referred to as no-touch or non-aggressive
            cleaning techniques, and why they are used to restore masonry and clean wooden
            structures without impacting their structure.
        </p>
        <div id="t1">Clean Up</div>
        <p>
            Assuming 6.5 hours of trigger time in a standard day how much media will be lieing around on the ground?  Medium and hard medias
            are consumed at a rate of 5-6 lbs. every minute, dry ice at about 3 lbs. every minute, and soda at about 1.5 lbs. every minute.
            Dry ice sublimates (changes directly from a solid physical state to a gas) and disappears, leaving nothing to clean up
            but the contaminant you've removed.  This is a very large factor to consider as it takes a lot of time to clean up the resulting
            media, and it may have to be disposed of if the contaminant is dangerous.
        </p>
        <div id="t1">Advantages</div>
        <p>
            <b>Cold</b> - dry ice is -78.5 celcius, and sticky substances like glue, tar, ink etc. are cleaned very
            effectively because the contaminant is frozen and then chips off!<br><br>

            <b>Thermal Shock</b> - dry ice will cause the surface and contaminant
            to change temperature at different rates causing a micro-thermal shock.  This helps to pull them apart
            so cleaning is more effective.<br><br>

            <b>Environmentally Friendly</b> - dry ice is made from recycled carbon dioxide and exists naturally in the
            atmosphere.  Because of this it is a very green solution!<br><br>

            <b>Buffers Smoke Smell</b> - soda works as a strong buffer for acids and bases, because of this the smell of
            smoke is greatly reduced in fire restoration work.<br><br>

            <b>Inexpensive</b> - power washing saves money as no specialized media is needed
        </p>
        <div id="t1">Disadvantages</div>
        <p>
            <b>Oxygen Displacement</b> - dry ice is made from carbon dioxide which will displace
            oxygen at a rate of ~ 800 cubic feet per hour.  As a result, additional PPE (personal protective equipment) is required
            if the work is being done in an enclosed space.<br><br>

            <b>Mould</b> - the water from power washing can cause mould issues if used indoors.<br><br>

            <b>Conductive</b> - water is electrically conductive unlike other blasting media.<br><br>

            <b>Environmentally UNfriendly</b> - solvents can be very toxic which is a great reason to use dry ice
            blasting instead.
        </p>
    </div>

    <!-- HOW BLASTING WORKS -->
    <div class="info"><h1 class="info_header">How Blasting Works</h1>

        <img class="border" align="right" alt="Example of the blasting/lifting process, courtesy of ColdJet" src="{{ asset('website/images/spatula.png') }}">
        Dry ice blasting is similar to power washing or sand blasting except it uses
        dry ice instead of water or sand respectively.  When the ice hits the cleaning
        surface several things happen.<br>
        <br>
        1. The dry ice particles immediately sublimate on contact by sucking
        some heat from the target.  The surface cools slightly causing a
        small pressure wave that helps lift the dirt off like a spatula. This is called a
        micro-thermal shock.<br>
        <br>
        2. When dry ice sublimates it expands from a solid to a gas taking
        up 600 x as much volume.  As you blast, the dry ice slips under the surface
        layer of dirt and then expands, causing a tremendous lifting force
        that cleans your target.<br>
        <br>
        3. Because dry ice is extremely cold ( -78.5 C ) it proves more effective
        against adhesives by freezing them so they will chip off more easily.<br>
        <br>
        Because of the above benefits dry ice is much more effective in specialized
        projects.  When under time constraints dry ice is very beneficial because there
        is no clean up, the dry ice simply sublimates into a gaseous form and disappears.
    </div>

    <!-- DRY ICE BLASTING ADVANTAGES -->
    <div class='info mt-5'>
        <h1 class='info_header'>Dry Ice Blasting Advantages</h1>

        <p>
            Dry Ice has many unique properties that make it the right choice for certain applications. Read
            about them below and see how it is already used to decide if blasting is the right choice for you.
        </p>

        <div id="t1" class="mt-4">No Secondary Waste</div>
        <p>
            Dry ice sublimates directly into a gaseous state leaving no residue behind like
            the other blasting solutions. Because of this there is no cleanup needed and jobs
            can be completed in a much shorter time. This is very beneficial when you need
            to keep your surfaces water free to prevent rust or mold, or in the event of machinery
            like printing presses where you can't afford to have sand particles left behind.
        </p>

        <div id="t1" class="mt-4">Environmentally Friendly</div>
        <p>
            Carbon Dioxide is the 4th most abundant gas found naturally in the atmosphere, it's
            everywhere! Dry ice (solid carbon dioxide) is a fantastic solution when your
            only other alternative could be toxic cleaning chemicals. Because it is created
            from reclaimed carbon dioxide, it doesn't add anything to the atmosphere and so
            doesn't contribute to the greenhouse effect.
        </p>

        <div id="t1" class="mt-4">Not Electrically Conductive</div>
        <p>
            Dry ice does not conduct electricity and can be used to clean machines, and
            electrical systems while they are still running! Because of this you won't need
            to even turn your systems off and lose any time on production.
        </p>

        <div id="t1" class="mt-4">Extreme Cold</div>
        <p>
            Dry ice is extremely cold ( -78.5 C ) which helps it remove glue
            like substances, graffiti, oils, and the like. The dry ice freezes the glue
            making it chip off much easier then using other media which would get stuck.
        </p>

        <div id="t1" class="mt-4">Less Abrasive</div>
        <p>
            Dry Ice blasting doesn't abrade surfaces like power washing or
            sand blasting. Because of this it won't ruin finishes, layers of
            paint, or leave deep blasting groves on your walls.
        </p>

        <div id="t1" class="mt-4">Clean in Place</div>
        <p>
            Dry ice blasters are portable and the hoses to the gun can reach 50 feet or
            longer. This allows you to clean equipment in place, preventing downtime and
            the need for a dedicated cleaning room.
        </p>

        <div id="t1" class="mt-4">Food Safe</div>
        <p>
            Dry ice blasters use food grade carbon dioxide and have been approved by the
            EPA, FDA, and USDA making them safe for cleaning kitchens, food processors
            and the like. Because dry ice is so cold it kills bacteria and fungi on
            contact, disinfecting without the use of any chemicals.
        </p>

        <div id="t1" class="mt-4">Faster and Safer</div>
        <p>
            Blasting with dry ice is substantially faster and cleans better then conventional
            scrubbing. In addition no chemicals or cleaners are used so it's more environmentally
            friendly.
        </p>
    </div>
</div>
@endsection

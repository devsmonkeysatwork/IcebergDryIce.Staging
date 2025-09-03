<!-- resources/views/dry-ice-information.blade.php -->

@extends('website.layouts.main')
<style>
    .row {
        display: flex;
    }
    .col-md-6 {
        width: 50%;
    }
</style>

@section('content')
    <div class="container">
        <!-- SMOKE EFFECTS -->
        <div class='info'>
            <h1 class='info_header'>Smoke Effects</h1>

            <p>
                Dry Ice can be used to create a very effective thick, ground-hugging fog great for parties, dance floors, boiling cauldrons
                fancy drinks and more. When dry ice warms up it cools the surrounding air and causes water vapor to condense, forming a cool white
                mist that sinks to the ground and stays there. If you put dry ice in a bowl of water this effect is multiplied many times over.
            </p>
            <p>
                Depending on the effect you want you'll need different amounts of dry ice as seen below.
            </p>

            <div id="t1">Punch Bowls</div>
            <img align="right" class="border" src="{{ asset('website/images/coast.jpg') }}" alt="Punch bowl with dry ice">
            <p>
                Punch bowls are the easiest effects to make, all you need to do is drop some dry ice in
                water and it will bubble and boil and make some fun smoke. Remember dry ice IS extremely cold and you should be
                very careful about reaching in and touching it without a glove. If you want to create a smoking punch bowl we
                recommend putting the dry ice in some kind of perforated container first so you can't accidently scoop it up and
                try to drink it.
            </p>
            <p>
                <b>Uses 1-2 lbs. for 10 minutes</b>
            </p>

            <div id="t1">Cauldrons</div>
            <img align="right" class="border" src="{{ asset('website/images/cauldron.jpg') }}" alt="Cauldron with dry ice effect">
            <p>
                Making a bubbling witches cauldron is the same as a punch bowl, with two exceptions. First you'll want to use
                more dry ice to get a more energetic reaction, and second you'll want to make sure your cauldron is closer to
                full so it's easier for the smoke to bubble out of the cauldron and course down the side. Check out this
                display made by <a target="_blank" href="https://www.creativeeyemedia.com/Home.html">Creative Eye Media</a>.
            </p>
            <p>
                <b>Uses 3-5 lbs. for 10 minutes</b>
            </p>
            <br>
            <br>

            <div id="t1">Dance Floors and Grave Yards</div>
            <img align="right" class="border" src="{{ asset('website/images/peasouper.jpg') }}" alt="Pea souper dry ice machine">
            <p>
                If you want to cover the ground with flowing smoke that billows out from a location there are several easy
                options, each one will of course use more dry ice then either a punch bowl or a cauldron.
            </p>
            <p>
                <b>Multiple Cauldrons</b>: You can create this effect by using several cauldrons full of dry ice that
                pump smoke out from different locations.
            </p>
            <p>
                <b>Dry Ice Smoke Machine</b>: You can rent a professional dry ice machine such as a Pea Souper that is
                designed to automatically lower dry ice into how water, and simultaneously heat the water to create a better
                effect.
            </p>
            <p>
                <b>Smoke Machines and Dry Ice</b>: Finally you could use a chemical smoke machine, and pump the smoke
                through a container full of dry ice too cool it so the smoke hugs the ground. Note with this solution the
                smoke is barely cold enough to stay on the ground so it works great for Halloween graveyard setups, but
                on a dance floor it gets kicked up into the air and doesn't hug the ground as well.
            </p>
            <p>
                <b>For videos of different amounts of dry ice producing smoke over different time periods check out the videos link for dry ice uses.</b>
            </p>
        </div>

        <!-- FOOD STORAGE -->
        <div class='info'>
            <h1 class='info_header'>Food Storage</h1>

            <p>
                The primary use for dry ice is food storage and cooling. Dry Ice is a great way to keep perishable goods fresh
                when you're not at home. It lasts 5x longer then
                regular ice and when it sublimates it turns into a harmless gas and disappears, there's no melted water
                to make your sandwiches soggy! Freezing your food first is a great idea as the dry ice keeps it frozen
                and you can combine it with ice packs and gel packs to keep food fresh for a week or longer.
            </p>
            <p>
                Separating dry ice from the food with newspapers or another insulator will help prevent freezer burn.
                For tips on how fast dry ice sublimates, and how to pack it for best effect check out our page on
                storage and melting. Additionally some
                shipping companies have recommended amounts of dry ice by weight and duration - here's an example from
                <a href="#">Continental Carbonic</a> in the United States.
            </p>
        </div>

        <!-- SCIENCE EXPERIMENTS -->
        <div class='info'>
            <h1 class='info_header'>Science!</h1>

            <div class="row">
                <div class="col-md-6">
                    <p>
                        Dry Ice is great for science experiements and other fun ideas.
                        Because dry ice is food safe it can be used for fun displays in cooking like this volcano cake!
                    </p>
                </div>
                <div class="col-md-6">
                    <img class="border img-fluid" src="{{ asset('website/images/volcano_cake.jpg') }}" alt="Volcano cake with dry ice">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/8tHOVVgGkpk?feature=player_detailpage" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <p>
                        Dry ice creates a fun water vapor that looks like smoke, look what happens when you combine
                        that with soap!
                    </p>
                    <p>
                        <i>Note this video was made by www.youtube.com/brusspup</i>
                    </p>
                </div>
            </div>
        </div>

        <!-- VIDEOS - EVENTS -->
        <div class='info'>
            <h1 class='info_header'>Videos</h1>

            <div class="row">
                <div class="col-md-6">
                    <p>
                        Here's an example of combining a smoke machine with dry ice to get a great low lieing fog effect. The
                        fog is pumped through a container holding dry ice to cool it down. Because cold air sinks you get this
                        great low lieing fog effect!
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/BtLPBotIjWs" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div id="t1" class="mt-4">Smoke Effects - Examples - 2 lbs from 0 - 20 minutes</div>
            <p>
                Examples of exactly what dry ice looks like when left in hot or cold water to help you estimate how much
                you will want to use for yourself. Note that smoke is formed energetically enough for the first 10 minutes that
                it will bubble over the edge of your container, and the dry ice is almost completely melted after 20 minutes.
            </p>

            <div class="row">
                <div class="col-md-6">
                    <h4>Hot Water : 0-1 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/Q_sSohj3KmQ" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>Hot Water : 5-6 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/3mgKbsDhOMY" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>Hot Water : 10-11 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/mmtCbhN1OqA" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>Hot Water : 15-16 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/BPIxzTs0Dk0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>Hot Water : 20-21 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/fZ9YYsuZdJQ" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4><span class="text-primary">Cold</span> Water : 0-1 Minutes</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/CMaHg9ytw90" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

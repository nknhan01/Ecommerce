<div class="ui-select-wrapper">
    @php
        Arr::set($selectAttributes, 'class', Arr::get($selectAttributes, 'class') . ' ui-select');
    @endphp
    <select name="{{ $name }}" class='form-control select2_google_fonts_picker'>
        @php
            $field['options'] = ['Aclonica', 'Allan', 'Annie Use Your Telescope', 'Anonymous Pro', 'Allerta Stencil', 'Allerta', 'Amaranth', 'Anton', 'Architects Daughter', 'Arimo', 'Artifika', 'Arvo', 'Asset', 'Astloch', 'Bangers', 'Bentham', 'Bevan', 'Bigshot One', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Buda:300', 'Cabin', 'Calligraffitti', 'Candal', 'Cantarell', 'Cardo', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Cherry Cream Soda', 'Chewy', 'Coda', 'Coming Soon', 'Copse', 'Corben:700', 'Cousine', 'Covered By Your Grace', 'Crafty Girls', 'Crimson Text', 'Crushed', 'Cuprum', 'Damion', 'Dancing Script', 'Dawning of a New Day', 'Didact Gothic', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'EB Garamond', 'Expletus Sans', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Geo', 'Give You Glory', 'Goblin One', 'Goudy Bookletter 1911', 'Gravitas One', 'Gruppo', 'Hammersmith One', 'Holtwood One SC', 'Homemade Apple', 'Inconsolata', 'Indie Flower', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Josefin Sans', 'Josefin Slab', 'Judson', 'Jura', 'Jura:500', 'Jura:600', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Kenia', 'Kranky', 'Kreon', 'Kristi', 'La Belle Aurore', 'Lato:100', 'Lato:100italic', 'Lato:300',  'Lato', 'Lato:bold',   'Lato:900', 'League Script', 'Lekton',   'Limelight',   'Lobster', 'Lobster Two', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Luckiest Guy', 'Maiden Orange', 'Mako', 'Maven Pro', 'Maven Pro:500', 'Maven Pro:700', 'Maven Pro:900', 'Meddon', 'MedievalSharp', 'Megrim', 'Merriweather', 'Metrophobic', 'Michroma', 'Miltonian Tattoo', 'Miltonian', 'Modern Antiqua', 'Monofett', 'Molengo', 'Montserrat','Mountains of Christmas', 'Muli:300',  'Muli',  'Neucha', 'Neuton', 'News Cycle', 'Nixie One', 'Nobile', 'Noto Sans', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Nunito:light', 'Nunito', 'Nunito Sans', 'OFL Sorts Mill Goudy TT', 'Old Standard TT', 'Open Sans:300', 'Open Sans', 'Open Sans:600', 'Open Sans:800', 'Open Sans Condensed:300', 'Orbitron', 'Orbitron:500', 'Orbitron:700', 'Orbitron:900', 'Oswald', 'Over the Rainbow', 'Reenie Beanie', 'Pacifico', 'Patrick Hand', 'Paytone One',  'Permanent Marker', 'Philosopher', 'Play', 'Playfair Display', 'Podkova', 'Poppins', 'PT Sans', 'PT Sans Narrow', 'PT Sans Narrow:regular,bold', 'PT Serif', 'PT Serif Caption', 'Puritan', 'Quattrocento', 'Quattrocento Sans', 'Radley', 'Raleway', 'Raleway:100', 'Redressed', 'Rock Salt', 'Rokkitt', 'Roboto', 'Ruslan Display', 'Schoolbell', 'Shadows Into Light', 'Shanti', 'Sigmar One', 'Six Caps', 'Slackey', 'Smythe', 'Sniglet:800', 'Special Elite', 'Stardos Stencil', 'Sue Ellen Francisco', 'Sunshiney', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Tenor Sans', 'Terminal Dosis Light', 'The Girl Next Door', 'Tinos', 'Ubuntu', 'Ultra', 'Unkempt', 'UnifrakturCook:bold', 'UnifrakturMaguntia', 'Varela', 'Varela Round', 'Vibur', 'Vollkorn', 'VT323', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Wire One', 'Yanone Kaffeesatz', 'Yanone Kaffeesatz:300', 'Yanone Kaffeesatz:400', 'Yanone Kaffeesatz:700', 'Yeseva One', 'Zeyada'];
        @endphp
        @foreach (array_combine($field['options'], $field['options']) as $key => $value)
            <option value='{{ $key }}' @if ($key == $selected) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
    <svg class="svg-next-icon svg-next-icon-size-16">
        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
    </svg>
</div>

@push('footer')
    <link href="https://fonts.googleapis.com/css?family={{ implode('|', array_map('urlencode', $field['options'])) }}" rel="stylesheet" type="text/css">
    <script>
        "use strict";
        jQuery(document).ready(function($) {
            $('.select2_google_fonts_picker').each(function (i, obj) {
                if (!$(obj).hasClass('select2-hidden-accessible')){
                    $(obj).select2({
                        templateResult: function (opt) {
                            if (!opt.id) {
                                return opt.text;
                            }
                            console.log(opt);
                            return $('<span style="font-family:\'' + opt.id + '\';"> ' + opt.text + '</span>');
                        },
                    })
                }
            });
        });
    </script>
@endpush


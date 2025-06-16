import './bootstrap.js';
import { startStimulusApp } from '@symfony/stimulus-bridge';
import liveComponent from '@symfony/ux-live-component';

const app = startStimulusApp();
app.register('live', liveComponent);
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/buttomCustom.css';
import './styles/login/login.css';
import './styles/partials/navbar.css';
import './styles/home/home.css';
import './styles/colors.css';
import './styles/star_rating.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

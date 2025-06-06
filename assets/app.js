import { registerReactControllerComponents } from '@symfony/ux-react';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/pages/home/home.css';
import './styles/pages/login/login.css';
import './styles/pages/profile/profile.css';
import './styles/pages/library/library.css';
import './styles/app.css';
import './styles/buttomCustom.css';
import './styles/partials/navbar.css';
import './styles/colors.css';
import './styles/star_rating.css';

import './bootstrap';
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
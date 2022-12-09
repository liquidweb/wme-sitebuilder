/**
 * Scripting for the SiteDetails React App
 */
import SiteDetails from '@moderntribe/sitedetails';

wp.element.render(
	wp.element.createElement(SiteDetails),
	document.getElementById('site-details-react'),
);

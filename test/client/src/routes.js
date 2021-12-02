import {ADMIN_ROUTE,BALANCE_ROUTE,HOME_ROUTE,REGISTRATHION_ROUTE,LOGIN_ROUTE} from './utils/consts';
import Admin from './pages/Admin';
import Auth from './pages/Auth';
import Home from './pages/Home';
import Balance from './pages/Balance';

export const authRoutes = [
	{
		path: ADMIN_ROUTE,
		Component: Admin
	},
	{
		path: BALANCE_ROUTE + '/:id',
		Component: Balance
	}
]	

export const publicRoutes = [

	{
		path: HOME_ROUTE,
		Component: Home
	},
	{
		path: REGISTRATHION_ROUTE,
		Component: Auth
	},
	{
		path: LOGIN_ROUTE,
		Component: Auth
	}
]
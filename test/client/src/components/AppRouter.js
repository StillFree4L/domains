import {Switch,Route,Redirect} from 'react-router-dom';
import {authRoutes,publicRoutes} from '../routes';

function AppRouter() {
  const isAuth = false
  return (
    <Switch>
          {isAuth && authRoutes.map(({path,Component}) =>
            <Route key={path} path={path} component={Component} />
            )}
          {publicRoutes.map(({path,Component}) =>
            <Route key={path} path={path} component={Component} />
            )}
    </Switch>
  );
}

export default AppRouter;

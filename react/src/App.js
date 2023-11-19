import {
  BrowserRouter as Router,
  Routes,
  Route,
  NavLink,
  Outlet,
  Navigate,
} from 'react-router-dom';
import { AppBar, Toolbar, Container, Button } from '@mui/material';
import { useSelector } from 'react-redux';

import HomeView from './views/HomeView';
import ProfileView from './views/ProfileView';
import LoginView from './views/LoginView';
import RegisterView from './views/RegisterView';
import SuccessView from './views/SuccessView';

import './style.css';

const App = () => {
  const user = useSelector((state) => state.auth.user);
  const AuthRoutes = () => {
    return user ? <Outlet /> : <Navigate to="/" />;
  };
  const GuestRoutes = () => {
    return !user ? <Outlet /> : <Navigate to="/" />;
  };
  return (
    <Router>
      <AppBar position="static" sx={{ marginBottom: 4, boxShadow: 'none' }}>
        <Toolbar className="nav">
          <Button
            component={NavLink}
            to="/"
            end
            activeClassName="active"
            color="inherit"
          >
            Home
          </Button>
          {!user && (
            <>
              <Button
                component={NavLink}
                to="/login"
                end
                activeClassName="active"
                color="inherit"
              >
                Login
              </Button>
              <Button
                component={NavLink}
                to="/register"
                end
                activeClassName="active"
                color="inherit"
              >
                Register
              </Button>
            </>
          )}
          {user && (
            <Button
              component={NavLink}
              to="/profile"
              end
              color="inherit"
              activeClassName="active"
              sx={{ marginLeft: 'auto' }}
            >
              {user.first_name}'s Profile
            </Button>
          )}
        </Toolbar>
      </AppBar>
      <Container sx={{ paddingBottom: 4 }}>
        <Routes>
          <Route index element={<HomeView />} />
          <Route element={<GuestRoutes />}>
            <Route path="login" element={<LoginView />} />
            <Route path="register" element={<RegisterView />} />
          </Route>
          <Route element={<AuthRoutes />}>
            <Route path="profile" element={<ProfileView />} />
            <Route path="/success" element={<SuccessView />} />
          </Route>
        </Routes>
      </Container>
    </Router>
  );
};

export default App;

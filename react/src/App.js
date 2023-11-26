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

import {
  HomeView,
  ProfileView,
  LoginView,
  RegisterView,
  SuccessView,
} from './views';
import { UsersView } from './views/admin';
import {
  UserToolbarButtons,
  GuestToolbarButtons,
  AdminToolbarButtons,
} from './components/Auth';
import './style.css';

const App = () => {
  const user = useSelector((state) => state.auth.user);
  const UserRoutes = () => {
    return user && !user.isAdmin ? <Outlet /> : <Navigate to="/" />;
  };
  const AdminRoutes = () => {
    return user && user.isAdmin ? <Outlet /> : <Navigate to="/" />;
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
            activeclassname="active"
            color="inherit"
          >
            Home
          </Button>
          {!user && <GuestToolbarButtons />}
          {user && !user.isAdmin && <UserToolbarButtons user={user} />}
          {user && user.isAdmin && <AdminToolbarButtons user={user} />}
        </Toolbar>
      </AppBar>
      <Container sx={{ paddingBottom: 4 }}>
        <Routes>
          <Route index element={<HomeView />} />
          <Route element={<GuestRoutes />}>
            <Route path="login" element={<LoginView />} />
            <Route path="register" element={<RegisterView />} />
          </Route>
          <Route element={<AdminRoutes />}>
            <Route path="/admin/users" element={<UsersView />} />
          </Route>
          <Route element={<UserRoutes />}>
            <Route path="profile" element={<ProfileView />} />
            <Route path="/success" element={<SuccessView />} />
          </Route>
        </Routes>
      </Container>
    </Router>
  );
};

export default App;

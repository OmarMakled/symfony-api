import { Button } from '@mui/material';
import { NavLink } from 'react-router-dom';

const GuestToolbarButtons = () => (
  <>
    <Button
      component={NavLink}
      to="/login"
      end
      color="inherit"
      activeclassname="active"
    >
      Login
    </Button>
    <Button
      component={NavLink}
      to="/register"
      end
      color="inherit"
      activeclassname="active"
    >
      Register
    </Button>
  </>
);

export default GuestToolbarButtons;

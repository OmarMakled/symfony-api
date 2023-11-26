import { Button } from '@mui/material';
import { NavLink } from 'react-router-dom';

const AdminToolbarButtons = ({ user }) => (
  <>
    <Button
      component={NavLink}
      to="/admin/users"
      end
      color="inherit"
      activeclassname="active"
    >
      Users
    </Button>
    <Button
      component={NavLink}
      to="/profile"
      end
      color="inherit"
      activeclassname="active"
      sx={{ marginLeft: 'auto' }}
    >
      {user.first_name}'s Profile
    </Button>
  </>
);

export default AdminToolbarButtons;

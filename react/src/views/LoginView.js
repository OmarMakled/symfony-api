import LoginForm from '../components/LoginForm';
import { Grid } from '@mui/material';

const LoginView = () => {
  return (
    <Grid container direction="row" justifyContent="center" alignItems="center">
      <Grid item xs={12} md={8}>
        <LoginForm />
      </Grid>
    </Grid>
  );
};

export default LoginView;

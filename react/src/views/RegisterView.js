import RegisterForm from '../components/RegisterForm';
import { Grid } from '@mui/material';

const RegisterView = () => {
  return (
    <Grid container direction="row" justifyContent="center" alignItems="center">
      <Grid item xs={12} md={8}>
        <RegisterForm />
      </Grid>
    </Grid>
  );
};

export default RegisterView;

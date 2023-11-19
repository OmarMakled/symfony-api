import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import {
  TextField,
  Button,
  Card,
  CardContent,
  Typography,
} from '@mui/material';
import { useFormik } from 'formik';
import * as yup from 'yup';
import { useNavigate } from 'react-router-dom';
import ErrorList from './ErrorList';
import { login, profile } from '../store/authThunks';

const Login = () => {
  const dispatch = useDispatch();
  const isSubmitting = useSelector((state) => state.auth.isSubmitting);
  const [responseError, setResponseError] = useState(null);
  const navigate = useNavigate();
  const validationSchema = yup.object({
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().required('Password is required'),
  });

  const formik = useFormik({
    initialValues: {
      email: '',
      password: '',
    },
    validationSchema: validationSchema,
    onSubmit: async (values) => {
      try {
        await dispatch(login(values)).unwrap();
        await dispatch(profile()).unwrap();

        navigate('/profile');
      } catch ({ error }) {
        setResponseError(error);
      }
    },
  });

  return (
    <Card>
      <CardContent>
        <Typography gutterBottom variant="h5" component="div">
          Login
        </Typography>
        <form onSubmit={formik.handleSubmit}>
          <ErrorList error={responseError} />
          <TextField
            label="Email"
            type="text"
            name="email"
            value={formik.values.email}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            error={Boolean(formik.touched.email && formik.errors.email)}
            helperText={formik.touched.email && formik.errors.email}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <TextField
            label="Password"
            type="password"
            name="password"
            value={formik.values.password}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            error={Boolean(formik.touched.password && formik.errors.password)}
            helperText={formik.touched.password && formik.errors.password}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <Button
            type="submit"
            variant="contained"
            color="primary"
            disabled={isSubmitting}
            fullWidth
          >
            Login
          </Button>
        </form>
      </CardContent>
    </Card>
  );
};

export default Login;

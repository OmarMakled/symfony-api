import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import {
  TextField,
  Card,
  CardContent,
  Button,
  styled,
  Typography,
  FormControl,
} from '@mui/material';
import { useNavigate } from 'react-router-dom';
import { useFormik } from 'formik';
import * as yup from 'yup';
import ErrorList from './ErrorList';
import { register, login, profile } from '../store/authThunks';

const VisuallyHiddenInput = styled('input')({
  clip: 'rect(0 0 0 0)',
  clipPath: 'inset(50%)',
  height: 1,
  overflow: 'hidden',
  position: 'absolute',
  bottom: 0,
  left: 0,
  whiteSpace: 'nowrap',
  width: 1,
});

const RegisterForm = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const isSubmitting = useSelector((state) => state.auth.isSubmitting);
  const [responseError, setResponseError] = useState(null);

  const validationSchema = yup.object({
    firstName: yup.string().min(2).max(25).required('First Name is required'),
    lastName: yup.string().min(2).max(25).required('Last Name is required'),
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup
      .string()
      .min(6, 'Password must be at least 6 characters')
      .max(50, 'Password must be at most 50 characters')
      .matches(/(?=.*[0-9])/, 'Password must contain at least one number')
      .required('Password is required'),
    avatar: yup.string().url('Invalid URL'),
    photos: yup
      .mixed()
      .nullable()
      .test('minPhotos', 'At least 4 photos are required', (value) => {
        if (!value) return true;
        if (typeof value === 'object' && Object.keys(value).length >= 4)
          return true;
        return false;
      }),
  });

  const formik = useFormik({
    initialValues: {
      firstName: '',
      lastName: '',
      email: '',
      password: '',
      avatar: '',
      photos: null,
    },
    validationSchema: validationSchema,
    onSubmit: async (values) => {
      try {
        await dispatch(register(values)).unwrap();
        await dispatch(login(values)).unwrap();
        await dispatch(profile()).unwrap();
        navigate('/success');
      } catch ({ error }) {
        setResponseError(error);
      }
    },
  });

  return (
    <Card>
      <CardContent>
        <Typography gutterBottom variant="h5" component="div">
          Register
        </Typography>
        <form onSubmit={formik.handleSubmit}>
          <ErrorList error={responseError} />
          <TextField
            type="text"
            label="First Name"
            name="firstName"
            value={formik.values.firstName}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            error={Boolean(formik.touched.firstName && formik.errors.firstName)}
            helperText={formik.touched.firstName && formik.errors.firstName}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <TextField
            type="text"
            label="Last Name"
            name="lastName"
            value={formik.values.lastName}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            error={Boolean(formik.touched.lastName && formik.errors.lastName)}
            helperText={formik.touched.lastName && formik.errors.lastName}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <TextField
            type="text"
            label="Email"
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
            type="password"
            label="Password"
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
          <TextField
            type="text"
            label="Avatar"
            name="avatar"
            value={formik.values.avatar}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            error={Boolean(formik.touched.avatar && formik.errors.avatar)}
            helperText={formik.touched.avatar && formik.errors.avatar}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <FormControl fullWidth margin="normal">
            <Button
              component="label"
              variant="outlined"
              color={
                formik.touched.photos && formik.errors.photos
                  ? 'error'
                  : 'primary'
              }
              size="large"
              fullWidth
            >
              {formik.touched.photos && formik.errors.photos
                ? formik.errors.photos
                : 'Upload file'}
              <VisuallyHiddenInput
                type="file"
                name="photos[]"
                onBlur={formik.handleBlur}
                onChange={(event) =>
                  formik.setFieldValue('photos', event.currentTarget.files)
                }
                multiple
              />
            </Button>
          </FormControl>
          <FormControl fullWidth margin="normal">
            <Button
              type="submit"
              variant="contained"
              color="primary"
              size="large"
              disabled={isSubmitting}
              fullWidth
            >
              Register
            </Button>
          </FormControl>
        </form>
      </CardContent>
    </Card>
  );
};

export default RegisterForm;

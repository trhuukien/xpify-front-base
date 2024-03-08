import { lazy, memo, Suspense } from 'react';
import { useSection } from '~/talons/section/useSection';
import Skeleton from '~/components/SectionDetails/skeleton';
const SectionDetails = lazy(() => import('~/components/SectionDetails'));

const SectionFullPageDetails = props => {
  const talonProps = useSection();
  if (!talonProps.section || Object.keys(talonProps.section).length === 0) return <Skeleton />;
  return (
    <Suspense fallback={<Skeleton />}>
      <SectionDetails {...talonProps} {...props} />
    </Suspense>
  );
};

export default memo(SectionFullPageDetails);

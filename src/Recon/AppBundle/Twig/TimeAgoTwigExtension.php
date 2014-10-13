<?php

namespace Recon\AppBundle\Twig;

use DateTime;

class TimeAgoTwigExtension extends \Twig_Extension{

    private $translator;

    public function __construct($translator){
        $this->translator = $translator;
    }

    public function getFilters(){
        return [
            'time_ago' => new \Twig_Filter_Method($this, 'timeAgo', ['is_safe' => ['html']]),
        ];
    }

    public function getName(){
        return "time_ago_twig_extension";
    }

    /**
     * Convert a time to time 'ago', such as 5 days, 1 week, etc.
     *
     * @param DateTime $date
     * @param int $granularity level of granularity (how far to drill down in exact time ago)
     * @param string $postText text to display after the time ago
     * @param DateTime $dateFrom if wanting time ago from a specific datetime rather than the current datetime
     * @return string|null null if the pasesd in date is earlier than the date to compare it to
     */
    public function timeAgo(DateTime $date, $granularity = 2, $postText = 'default', DateTime $dateFrom = null){

        if($postText === 'default'){
            $postText = $this->translator->trans('date.ago');
        }

        // interval array matching date format and corresponding type
        $intervals = [
            'y' => $this->translator->trans('date.year'),
            'ys' => $this->translator->trans('date.years'),
            'm' => $this->translator->trans('date.month'),
            'ms' => $this->translator->trans('date.months'),
            'd' => $this->translator->trans('date.day'),
            'ds' => $this->translator->trans('date.days'),
            'h' => $this->translator->trans('date.hour'),
            'hs' => $this->translator->trans('date.hours'),
            'i' => $this->translator->trans('date.minute'),
            'is' => $this->translator->trans('date.minutes'),
            's' => $this->translator->trans('date.second'),
            'ss' => $this->translator->trans('date.seconds'),
        ];
        $i = 0;
        $result = '';

        // default to find time against the current datetime
        if(!$dateFrom){
            $dateFrom = new DateTime();
        }

        // make sure accuracy is between 1 and 6
        $granularity = (int) $granularity;
        if($granularity < 1 || $granularity > 6){
            $granularity = 1;
        }

        // if the datetime passed in is later than the datetime comparing, return null
        if($date > $dateFrom){
            return null;
        }

        // get the difference between the two dates
        $difference = $dateFrom->diff($date);

        // now check each interval to see if the date difference contains that interval
        foreach(['y', 'm', 'd', 'h', 'i', 's'] as $interval){

            // only add to the time ago string if the interval is contained in the date difference
            if($difference->$interval >= 1){
                $result .= ' ' . $difference->$interval . ' ' . $intervals[$interval . ($difference->$interval != 1 ? 's' : '')];
                $i++;
            }

            // if we have reached the maximum level of granularity, stop building the string
            if($i == $granularity){
                break;
            }
        }

        // now add any suffix to the result string (1 day <postText> => 1 day ago)
        if(strlen($postText)){
            $result .= ' ' . $postText;
        }

        // return the trimmed result (the result starts with whitespace)
        return trim($result);
    }

}

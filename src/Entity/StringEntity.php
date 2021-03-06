<?php declare(strict_types=1);

namespace Stub\Entity;

use Stub\Entity;
use Stub\EntityInterface;
use Stub\Storage;
use Stub\Tokenizer;
use Stub\Token\TokenIterator;
use Stub\Token\Traverse\Criteria;

final class StringEntity extends Entity implements EntityInterface
{
    /**
     * @var string
     */
    protected $type = Storage::S_STRING;

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->format();
    }

    /**
     * @param  TokenIterator $tokenIterator
     * @return void
     */
    public function add(TokenIterator $tokenIterator)
    {
        $input = $tokenIterator->current();

        switch ($input) {
            case '<?php':
                break;
            case 'define':
                $input = str_replace(
                    ',',
                    ', ',
                    implode('', $tokenIterator->seekUntil(new Criteria(Tokenizer::SEMICOLON)))
                );
                break;
            default:
                $input = '';
                break;
        }

        $this->data[] = $input;
    }

    /**
     * @return string
     */
    private function format() : string
    {
        return implode('', $this->current());
    }
}
